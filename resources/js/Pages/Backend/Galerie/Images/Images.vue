<template>
    <section>
        <Dashboard />
    </section>
    <v-container>
        <v-row no-gutters>
            <v-col cols="8" sm="6" v-for="(name, index) in uniqueNameProduct" :key="index">
                <v-sheet class="ma-2 pa-2">
                    <div class="d-flex justify-center mb-2" v-if="images.length >= 1">
                        <v-list>
                            <v-list-item-group v-for="(productName, idx) in name.split(' ')" :key="idx">
                                <v-list-item :key="index">
                                    <v-list-item-title>
                                        <v-card>
                                            <v-toolbar class="bg-transparent">
                                                <v-toolbar-title>{{ productName }}</v-toolbar-title>

                                                <v-spacer></v-spacer>

                                                <v-btn icon variant="plain" id="menu-activator">
                                                    <v-icon icon="fas fa-ellipsis-vertical"></v-icon>
                                                </v-btn>

                                                <v-menu activator="#menu-activator">
                                                    <v-list>
                                                        <v-list-item>
                                                            <v-btn icon variant="plain">
                                                                <v-icon icon="fas fa-trash fa-2xs"
                                                                    @click="deleteGaleryDialog(image)"></v-icon>
                                                            </v-btn>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>

                                                <v-dialog v-model="deleteGalery">
                                                    <v-card class="d-flex justify-center mx-auto mb-6">
                                                        <v-card-text>

                                                            <p>Tem certeza que quer remover as imagens dessa galeria ? {{
                                                                image.product_name }}</p>
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

                                            <v-card-text>
                                                <v-container>
                                                    <v-row no-gutters>
                                                        <v-col cols="6" md="6" sm="6" v-for="(image, index) in images"
                                                            :key="index">
                                                            <v-hover v-slot="{ isHovering, props }">
                                                                <v-card class="d-flex align-center mb-2 mx-auto flex-column"
                                                                    v-bind="props">
                                                                    <v-img v-if="image.product_name === productName"
                                                                        :src="'storage/products/' + image.name + '.' + image.extension"
                                                                        :lazy-src="'./storage/products/' + image.name + '.' + image.extension"
                                                                        class="align-end"
                                                                        gradient="to bottom, rgba(0, 0, 0, .1), rgba(0,0,0,.5)"
                                                                        :width="250" :height="250" aspect-ratio="16/9"
                                                                        cover>
                                                                        <v-expand-transition>
                                                                            <div v-if="isHovering"
                                                                                class="d-flex transition-fast-in-fast-out bg-grey-darken-4 v-card-img--reveal text-2"
                                                                                style="height: 100%; width: 100%;">

                                                                                <v-btn-group color="grey-lighten-2">
                                                                                    <v-btn class="mb-2" icon variant="plain"
                                                                                        @click="previewDialog(image)"
                                                                                        size="small">
                                                                                        <v-icon icon="fas fa-eye"></v-icon>
                                                                                    </v-btn>
                                                                                    <v-btn icon variant="plain" class="mb-2"
                                                                                        size="small">
                                                                                        <v-icon icon="fa-solid fa-trash"
                                                                                            @click="deleteItem(image)">
                                                                                        </v-icon>
                                                                                    </v-btn>
                                                                                </v-btn-group>

                                                                                <div>
                                                                                    <v-dialog v-model="preview" :width="900" :height="720" class="d-flex justify-center mb-2 mx-auto flex-column">
                                                                                            <v-card>
                                                                                                <v-text>
                                                                                                    <v-img
                                                                                                    class="mx-auto fill-height"
                                                                                                    aspect-ratio="4/3"
                                                                                                    cover
                                                                                                    :src="`./storage/products/${this.editedItem.name}.${this.editedItem.extension}`"
                                                                                                    :lazy-src="`./storage/products/${this.editedItem.name}.${this.editedItem.extension}`">
                                                                                                    
                                                                                                    <v-spacer></v-spacer>

                                                                                                        <v-btn color="primary" icon id="menu-activator" @click="preview = false" size="x-small">
                                                                                                            <v-icon icon="fas fa-close"></v-icon>
                                                                                                        </v-btn>
                                                                                                    <template
                                                                                                        v-slot:placeholder>
                                                                                                        <div
                                                                                                            class="d-flex align-center justify-center fill-height">
                                                                                                            <v-progress-circular
                                                                                                                color="grey-lighten-4"
                                                                                                                indeterminate>
                                                                                                            </v-progress-circular>
                                                                                                        </div>
                                                                                                    </template>
                                                                                                    
                                                                                                </v-img>
                                                                                                </v-text>
                                                                                           
                                                                                       </v-card>


                                                                                    </v-dialog>
                                                                                </div>
                                                                            </div>
                                                                        </v-expand-transition>
                                                                    </v-img>

                                                                </v-card>
                                                            </v-hover>
                                                            <v-dialog v-model="dialogDelete" max-width="500px">
                                                                <v-card>
                                                                    <v-card-title class="text-h5">Are you sure you want to
                                                                        delete this
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
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>


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
import { toHandlers } from 'vue';
export default {
    components: {
        Dashboard
    },
    data: () => ({
        products: [],
        images: [],
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {},
        defaultItem: {},
        deleteGalery: false,
        preview: false,
    }),
    watch: {
        dialogDelete(val) {
            val || this.closeDelete();
        },
    },
    computed: {
        uniqueNameProduct() {
            const uniqueNames = [...new Set(this.images.map(item => item.product_name))];
            return uniqueNames.map(name => name.trim());
        }
    },
    methods: {
        getImages() {
            axios.get('/images')
                .then((response) => {
                    console.log(response.data);
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
        deleteGaleryDialog(image) {
            this.editedIndex = this.images.indexOf(image);
            this.editedItem = Object.assign({}, image);
            this.deleteGalery = true;
        },
        deleteGaleryConfirm() {
            const token = document.head.querySelector('meta[name="csrf-token"]');
            axios.delete(`/api/images/deleteAll/${this.editedItem.product_id}`)
                .then((response) => {
                    this.images.splice(this.editedItem);
                    return true;
                })
                .catch((response) => {
                    alert('Error:' + response);
                });
            this.closeGaleryDialog();

        },
        closeGaleryDialog() {
            this.deleteGalery = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1;
            });
        },
        previewDialog(item) {
            this.editedIndex = this.images.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.preview = true;
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
            });
        },
    },
    created() {
        // this.getProducts();
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