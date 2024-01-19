<template>
    <v-dialog>
        <v-container fluid>
            
                <v-row no-gutters>
                    <v-col class="d-flex justify-center d-flex" cols="12">
                        <v-card class="mx-auto">
                            <v-card-text>
                                Remove Item store ?
                            </v-card-text>

                            <v-card-actions>
                                <v-btn-group>
                                    <v-btn @click="remove(store)">
                                        Remove 
                                    </v-btn>
                                    <v-btn @click="close()">
                                        close
                                    </v-btn>
                                </v-btn-group>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            
        </v-container>
    </v-dialog>
</template>

<script>
import axios from 'axios';

    export default {
        props: ['store'],
        methods: {
            close(){
                this.$emit('close-dialog');
            },
            remove(item){
                axios.delete(`/store/delete/${item.id}`)
                .then((response) => {
                    this.close();
                    this.$emit('remove-store', item);
                    return true;
                })
                .catch((response) => {
                    alert('Error: ' + response);
                    return false;
                })
            }
        }
    }
</script>