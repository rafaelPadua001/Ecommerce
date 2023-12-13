<template>
    <v-container>
        <div>
            <Dashboard />
        </div>
        <v-row>
            <v-col class="d-flex justify-center flex-column">
                <v-sheet class="ma-2" cols="12" md="8" sm="2">
                    <v-card class="mx-auto" :max-width="800">
                        <v-card-title>
                            <v-toolbar title="delivery" class="bg-transparent-variant" prominent>
                                <template v-slot:append>
                                    <v-btn-group>
                                        <v-btn variant="plain" color="primary" @click="newItemDialog">new Item</v-btn>
                                    </v-btn-group>
                                </template>
                            </v-toolbar>
                        </v-card-title>

                        <v-spacer></v-spacer>
                        <v-divider></v-divider>

                        <v-card-text>
                            <v-row v-if="Object.keys(deliveries).length >= 1">
                                <v-col v-for="delivery in deliveries" :key="delivery.id" class="d-flex justify-center flex-column" cols="12">
                                    <v-sheet>
                                        <v-card>
                                            {{deliveries}}
                                        </v-card>
                                    </v-sheet>
                                </v-col>
                            </v-row>

                            <v-row v-else>
                                <v-col class="d-flex justify-center flex-column" cols="12">
                                    <v-sheet>
                                        <v-card>
                                           No delivery registered
                                        </v-card>
                                    </v-sheet>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-sheet>
            </v-col>
        </v-row>

        <div>
            <v-dialog v-model="createDialog">
                <NewItem :createDialog="createDialog"  @create="create" @close-dialog="closeDialog"/>
            </v-dialog>
        </div>
    </v-container>
   

</template>

<script>
    import Dashboard from '../Auth/Dashboard.vue';
    import NewItem from './Forms/NewItem.vue';

    export default {
        components: {
            Dashboard,
            NewItem
        },
        emits: ['create', 'close-dialog'],
        data: () => ({
            deliveries: [],
            createDialog: false,
        }),
        methods: {
            getDelivery(){
                axios.get('/delivery')
                .then((response) => {
                    return this.deliveries = response.data;
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
            },
            newItemDialog() {
                this.createDialog = true;
            },
            create(response){
              
                return this.deliveries.push(response.data);
            },
            closeDialog(){
                this.createDialog = false;
            }
        },
        created(){
            this.getDelivery();
        }
    }
</script>