<template>
  <v-layout class="rounded rounded-md" v-if="!user">
     <v-app-bar
        color="teal-darken-4"
        v-if="!user"
      >
        <template v-slot:image>
          <v-img
            gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"
          ></v-img>
        </template>

        <template v-slot:prepend>
        <!-- adicionar Logo aqui -->
          <a href="/"><v-icon icon="fa-solid fa-house fa-2xs" color="white"></v-icon></a>
        </template>

        <v-app-bar-title> <v-btn class="plain" color="white" href="/">EcommerceClone</v-btn></v-app-bar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon icon="fa-solid fa-magnifying-glass"></v-icon>
        </v-btn>
 
        <v-btn icon>
          <v-icon icon="fa-solid fa-cart-shopping"></v-icon>
        </v-btn>

        <!-- Cria o botao de menu do usuario -->
        <v-menu>
          <template v-slot:activator="{ props }">

            <v-btn 
              icon
              v-bind="props"
            >
              <v-icon icon="fa-solid fa-user"></v-icon>
            </v-btn>
        
          </template>
          <v-list>
          <!-- login button -->
            <v-list-item to="/login" v-if="!user">
              <v-list-item-title link>
                <span>
                  <v-icon icon="fa-solid fa-right-to-bracket fa-xs"></v-icon>
                </span>
                <span>Login</span>
              </v-list-item-title>
            </v-list-item>

            <!-- logout button -->
            
            <v-list-item @click="logout()" v-if="!user">
                <v-list-item-title link>
                <span>
                  <v-icon icon="fas fa-right-from-bracket"></v-icon>
                </span>
                
                <span>Logout</span>
              </v-list-item-title>
            </v-list-item>

            <!-- logout admin button -->
            <v-list-item @click="adminLogout()" v-if="user">
                <v-list-item-title link>
                <span>
                  <v-icon icon="fas fa-right-from-bracket"></v-icon>
                </span>
                
                <span>Admin Logout</span>
              </v-list-item-title>
            </v-list-item>
          </v-list>

          
        </v-menu>
      </v-app-bar>
      
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;" v-if="user">
       
      
        <v-container>
          <router-view></router-view>
          
        </v-container>
      </v-main>
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;" v-else>
       
      
        
         
       <router-view></router-view>
       
  
   </v-main>
  </v-layout>

  <v-layout class="rounded rounded-md" v-else>
      
      
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
       
      
        
         
          <router-view></router-view>
          
     
      </v-main>

  </v-layout>
  

</template>

<script>
import Dashboard from './Pages/Backend/Auth/Dashboard.vue';

   export default{
      components: {
        Dashboard,
      },
      data: () => ({
          user: '',
      }),
      methods: {
      getAuth(){
        axios.get('/users')
        .then((response) => {
          this.user = response.data;
          return true;
        })
        .catch((response) => {
          return false;
          console.log(response.data);
        });
      },
      logout() {
        const token = document.head.querySelector('meta[name="csrf-token"]');
                if (token) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                } else {
                    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
                }
        axios.post('/logoutCustomer', { withCredentials: true })
        .then((response) => {
          alert('aguarde um instante, estamos limpando os nossos registros....');
          console.log('disparando em 1 segundo');
          setTimeout(() => {
            
            window.location = '/login';
          }, 1000);
          
        })
        .catch((response) => {
          
          if(response.response.status == '401'){
            alert('Você precisa iniciar a sessão.');
          }
          return false;
        });
      },
      adminLogout() {
        const token = document.head.querySelector('meta[name="csrf-token"]');
          if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
          } else {
             console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
          }
        axios.get('/logout', { withCredentials: true })
        .then((response) => {
          alert('aguarde um instante, estamos limpando os nossos registros....');
          console.log('disparando em 1 segundo');
          setTimeout(() => {
            
            window.location = '/admin';
          }, 10000);
          
        })
        .catch((response) => {
          
          if(response.response.status == '401'){
            alert('Você precisa iniciar a sessão.');
          }
          return false;
        });
      }
    },
    created() {
        this.getAuth();
        
    }
 }
</script>