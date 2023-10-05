<template>
  
  <v-layout class="rounded rounded-md" v-if="user">
      
      
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
        <router-view></router-view>
      </v-main>

  </v-layout>
  <v-layout class="rounded rounded-md" v-else>
     
      
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;" v-if="user">
       
      
        <v-container>
          <router-view></router-view>
          
        </v-container>
      </v-main>
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;" v-else>
       
      
        
         
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
          user: [],
      }),
      methods: {
      getAuth(){
        axios.get('/users')
        .then((response) => {
          this.user = response.data;
         
          return true;
        })
        .catch((response) => {
          this.user = response.data;
          return false;
          
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