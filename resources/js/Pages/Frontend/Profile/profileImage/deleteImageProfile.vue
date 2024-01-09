<template>
   
            <v-dialog>
                <v-row fluid>
                    <v-col class="d-flex justify-center mb-6 flex-column" cols="auto" md="8">
                <v-card class="mx-auto text-center" :max-width="1800">
                    <v-card-title>
                       <v-toolbar>
                            Remove Image
                            <template v-slot:append>
                                <v-btn icon>
                                    <v-icon icon="fas fa-close" @click="close()">

                                    </v-icon>
                                </v-btn>
                            </template>
                        </v-toolbar>
                        
                    </v-card-title>

                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="12">
                                remove this {{ image.name }} ?
                            </v-col>
                        </v-row>
                       
                    </v-card-text>

                    <v-card-actions>
                        <v-btn-group>
                            <v-btn class="me-2" variant="plain" color="error" @click="remove(image)">
                                Remove 
                            </v-btn>
                            <v-btn class="me-2" variant="plain"  @click="close()">
                                Cancel
                            </v-btn>
                        </v-btn-group>
                    </v-card-actions>

                </v-card>
            </v-col>
    </v-row>
            </v-dialog>
       
</template>

<script>
import axios from 'axios';

    export default {
        props: ['image'],
        data: () => ({}),
        watch:{
            closeDialog(val){
                val || this.close();
            }
        },
        methods: {
            close(){
                this.$emit('close-dialog');
            },
            remove(image){
                axios.delete(`/profileImage/delete/${image.id}`)
                .then((response) => {
                    this.close();
                    return this.$emit('delete-image', image);
                   
                    //return this.image.splice(this.imageId, 1);
                })
                .catch((response) => {
                return alert('Error :' + response);
                });
            }
        },
    }
</script>