<template>
    <v-container>
        <v-sheet class="py-1 px-1">
            <v-row>
                <v-col class="d-flex justify-center flex-column">
                    <Dashboard />
                </v-col>
            </v-row>
            <v-row justify="center" no-gutters>
                <h4>Galery Images</h4>
                <v-divider></v-divider>
                <v-spacer></v-spacer>
                <v-col class="d-flex justify-center flex-column" cols="auto" sm="4"
                    v-for="(name, index) in uniqueNameProduct" :key="index">
                    <v-sheet class="ma-auto pa-1">
                        <div v-if="images && images.length >= 1">

                            <v-list>
                                <v-list-item-group v-for="(productName, idx) in name.split(' ')" :key="idx">
                                    <v-list-item :key="index">
                                        <v-card class="mx-1 my-1" :max-width="600">
                                            <v-toolbar class="bg-transparent">
                                                <v-toolbar-title>{{ productName }}</v-toolbar-title>

                                                <v-spacer></v-spacer>

                                                <template v-slot:append>

                                                    <v-btn icon variant="plain" :id="'menu-activator-' + index">
                                                        <v-icon icon="fas fa-ellipsis-vertical"></v-icon>
                                                    </v-btn>

                                                    <v-menu :activator="'#menu-activator-' + index">
                                                        <v-list>
                                                            <v-list-item>
                                                                <v-btn icon variant="plain">
                                                                    <v-icon icon="fas fa-trash fa-2xs"
                                                                        @click="deleteGaleryDialog(image)">
                                                                    </v-icon>

                                                                </v-btn>
                                                            </v-list-item>
                                                        </v-list>
                                                    </v-menu>

                                                    <v-dialog v-model="deleteGalery" :key="'dialog-' + index">
                                                        <v-card class="d-flex justify-center mx-auto mb-6">
                                                            <v-card-text>
                                                                <p>Tem certeza que quer remover as imagens dessa galeria
                                                                    ?
                                                                    {{ image.product_name }}
                                                                </p>
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
                                                </template>
                                            </v-toolbar>
                                            <v-divider></v-divider>
                                            <v-card-text>
                                                <v-row justify="center" no-gutters>
                                                    <v-col class='d-flex flex-child flex-column' cols="auto"
                                                        v-for="(image, index) in images" :key="index">
                                                        <v-hover v-slot="{ isHovering, props }">
                                                            <v-card class="d-flex align-center mx-auto flex-column"
                                                                v-bind="props">
                                                                <v-img v-if="image.product_name === productName"
                                                                    :src="'storage/products/' + image.name + '.' + image.extension"
                                                                    :lazy-src="'./storage/products/' + image.name + '.' + image.extension"
                                                                    class="align-start"
                                                                    gradient="to bottom, rgba(0, 0, 0, .1), rgba(0,0,0,.5)"
                                                                    :width="150" aspect-ratio="1" cover>
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
                                                                                <v-dialog v-model="preview" :width="900"
                                                                                    :height="720"
                                                                                    class="d-flex justify-center mb-2 mx-auto flex-column">
                                                                                    <v-card>
                                                                                        <v-text>
                                                                                            <v-img
                                                                                                class="mx-auto fill-height"
                                                                                                aspect-ratio="4/3" cover
                                                                                                :src="`./storage/products/${this.editedItem.name}.${this.editedItem.extension}`"
                                                                                                :lazy-src="`./storage/products/${this.editedItem.name}.${this.editedItem.extension}`">

                                                                                                <v-spacer></v-spacer>

                                                                                                <v-btn color="primary"
                                                                                                    icon
                                                                                                    id="menu-activator"
                                                                                                    @click="preview = false"
                                                                                                    size="x-small">
                                                                                                    <v-icon
                                                                                                        icon="fas fa-close"></v-icon>
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

                                            </v-card-text>
                                        </v-card>

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

                <v-infinite-scroll :color="'primary'" ref="infinite" :height="100" :width="1800" @load="load"
                    hide-scroll>

                    <template v-slot:empty>

                        <v-alert class="bg-transparent"> No more items to load</v-alert>
                    </template>
                </v-infinite-scroll>
            </v-row>
        </v-sheet>
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