<template>
    <div>
        <Dashboard />
    </div>

        <v-container>
            <v-row fluid justify="center">
                <v-col class="d-flex justify-center flex-column" cols="12">
                    <v-sheet>
                        <v-card class="mx-auto">
                            <v-card-title>
                                <v-toolbar>
                                    <v-toolbar-title>Settings</v-toolbar-title>
                                </v-toolbar>
                            </v-card-title>

                            <v-card-text>
                                Template de configuração da loja aqui
                            </v-card-text>
                           
                        </v-card>
                       
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
   
    
</template>

<script>
    import Dashboard from '../Auth/Dashboard.vue';
    export default {
        components: {
            Dashboard
        }
    }
</script>