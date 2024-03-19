<template>
    <v-app-bar name="app-bar" color="deep-purple-accent-4" v-if="user">
        <v-list-item prepend-icon="fas fa-house fa-xs" value="shared" to="/adminDashboard">
           
        </v-list-item>
      
        <template v-slot:append>
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props"> {{ user.name }} </v-btn>
                 </template>
                <v-list>
                    <v-list-item>
                        <v-list-item-title>
                            <span>
                                <v-icon icon="fas fa-right-from-bracket fa-2xs"></v-icon>
                            </span>
                            <span>
                                <v-btn variant="plain" @click="logout()">logout</v-btn>
                               
                            </span>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>


        </template>
    </v-app-bar>
</template>

<script>
export default {
    data: () => ({
        user: [],
    }),
    methods: {
        getAuth() {
            axios.get('/users')
                .then((response) => {
                    this.user = response.data;

                })
                .catch((response) => {
                    console.log(response.data);
                    return false;
                });
        },
        logout() {
        axios.post('/logout')
        .then((response) => {
       //   alert('aguarde um instante, estamos limpando os nossos registros....');
       //   console.log('disparando em 1 segundo');
          setTimeout(() => {
            window.location = '/admin';
          }, 1000);
          
        })
        .catch((response) => {
          
          if(response.response.status == '401'){
            alert('Você precisa iniciar a sessão.');
          }
          return false;
        });
      },
    },
    
    created() {
        this.getAuth();

    }
}
</script>