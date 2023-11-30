<template>
    <section>
        <Dashboard />
    </section>
    <v-container>
        <v-row no-gutters>
            <v-col cols="12" sm="12">
                <v-sheet class="ma-2 pa-2">
                    <div class="d-flex justify-center mb-6" v-if="images.length >= 1">
                        <div v-for="(image, index) in images" :key="image.id">
                            <v-card v-if="index === 1">
                                <v-card-title>

                                <v-toolbar class="bg-transparent">
                                    <v-toolbar-title>{{ image.product_name }}</v-toolbar-title>

                                    <v-spacer></v-spacer>

                                    <v-btn icon variant="plain" id="menu-activator">
                                        <v-icon icon="fas fa-ellipsis-vertical"></v-icon>
                                    </v-btn>

                                    <v-menu activator="#menu-activator">
                                        <v-list>
                                            <v-list-item>
                                                <v-btn icon variant="plain">
                                                    <v-icon icon="fas fa-trash fa-2xs" @click="deleteGaleryDialog(image)"></v-icon>
                                                </v-btn>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>

                                    <v-dialog v-model="deleteGalery">
                                        <v-card class="d-flex justify-center mx-auto mb-6">
                                            <v-card-text>
                                                
                                                <p>Tem certeza que quer remover as imagens dessa galeria ? {{ image.product_name }}</p>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-btn-goup>
                                                    <v-btn variant="plain" @click="deleteGaleryConfirm">
                                                        Remover
                                                    </v-btn>
                                                    <v-btn variant="plain" @click="closeGaleryDialog">
                                                        Cancelar
                                                    </v-btn>
                                                </v-btn-goup>
                                            </v-card-actions>
                                        </v-card>
                                        
                                    </v-dialog>
                                </v-toolbar>

                                </v-card-title>
                                <v-card-text>
                                <v-container>
                                    <v-row fluid>
                                        <v-col cols="12" md="6" sm="3" v-for="(image, index) in images" :key="index">
                                            <v-hover v-slot="{ isHovering, props }">
                                                <v-card class="mx-auto" :width="150" v-bind="props">
                                                    <v-img :src="'storage/products/' + image.name + '.' + image.extension"
                                                        :lazy-src="'./storage/products/' + image.name + '.' + image.extension"
                                                        class="align-end"
                                                        gradient="to bottom, rgba(0, 0, 0, .1), rgba(0,0,0,.5)"
                                                        :height="100" cover>
                                                        <v-expand-transition>
                                                            <div v-if="isHovering"
                                                                class="d-flex transition-fast-in-fast-out bg-grey-darken-4 v-card-img--reveal text-2"
                                                                style="height: 100%;">
                                                              

                                                                <v-btn-group color="grey-lighten-2">
                                                                    <v-btn icon variant="plain">
                                                                        <v-icon icon="fas fa-eye"></v-icon>
                                                                    </v-btn>
                                                                    <v-btn icon variant="plain" class="mb-2">
                                                                    <v-icon icon="fa-solid fa-trash fa-2xs"
                                                                        @click="deleteItem(image)">
                                                                    </v-icon>
                                                                </v-btn>
                                                                </v-btn-group>
                                                               
                                                            </div>
                                                        </v-expand-transition>
                                                    </v-img>

                                                </v-card>
                                            </v-hover>
                                            <v-dialog v-model="dialogDelete" max-width="500px">
                                                <v-card>
                                                    <v-card-title class="text-h5">Are you sure you want to delete this
                                                        item?</v-card-title>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="blue-darken-1" variant="text"
                                                            @click="closeDelete">Cancel</v-btn>
                                                        <v-btn color="blue-darken-1" variant="text"
                                                            @click="deleteItemConfirm">OK</v-btn>
                                                        <v-spacer></v-spacer>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </div>
                    </div>
                    <div v-else>
                        <v-card class="d-flex justify-center text-center mb-6">
                            <v-card-text>
                                 Nenhuma imagem de produto nos registros
                            </v-card-text>
                        </v-card>
                       
                    </div>
                </v-sheet>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from 'axios';
import Dashboard from '../../Auth/Dashboard.vue';
export default {
    components: {
        Dashboard
    },
    data: () => ({
        products: [],
        images: [],
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {
            name: '',
            created: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        defaultItem: {
            name: '',
            created: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        deleteGalery: false,
    }),
    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    methods: {
        getImages() {
            axios.get('/images')
                .then((response) => {
                    return this.images = response.data;
                })
                .catch((response) => {
                    alert('Error :' + response);
                });
        },
        getProducts() {
            axios.get('/products/show')
                .then((response) => {
                    return this.products = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                });
        },
        deleteGaleryDialog(image){
            this.editedIndex = this.images.indexOf(image);
            this.editedItem  = Object.assign({}, image);
            this.deleteGalery = true;
        },
        deleteGaleryConfirm(){
            const token = document.head.querySelector('meta[name="csrf-token"]');
            axios.delete(`/api/images/deleteAll/${this.editedItem.product_id}`)
            .then((response) => {
               return true; 
            })
            .catch((response) => {
                alert('Error:' + response);
            }); 
            this.closeGaleryDialog();
            return this.images.splice(this.editedIndex, 1);
        },
        closeGaleryDialog(){
            this.deleteGalery = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            });
        },
        deleteItem(item) {
            this.editedIndex = this.images.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        deleteItemConfirm() {
            axios.delete(`/api/images/delete/${this.editedItem.id}`)
                .then((response) => {
                    return true;
                })
                .catch((response) => {
                    alert('Error:'.response);
                    return false;
                });

            this.images.splice(this.editedIndex, 1)
            this.closeDelete()
        },
        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
    },
    created() {
        this.getProducts();
        this.getImages();

    }
}
</script>

<style>
.v-card-img--reveal {
    align-items: center;
    top: 0;
    justify-content: center;
    opacity: .9;
    position: absolute;
    width: 100%;
}
</style>