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
                        <div v-if="images && images.length >= 1">
                            <productImageList
                                :name="name"
                                :index="index"
                                :images="this.images"
                               
                                @delete-galery-dialog="deleteGaleryDialog"
                            />

                        </div>
                        <div v-else>
                            <v-card class="d-flex justify-center text-center mb-6">
                                <v-card-text>
                                    Nenhuma imagem de produto nos registros
                                </v-card-text>
                            </v-card>
                        </div>


                   
                </v-col>

                <v-infinite-scroll :color="'primary'" ref="infinite" :height="100" :width="1800" @load="load"
                    hide-scroll>

                    <template v-slot:empty>
                        <v-alert class="bg-transparent"> No more items to load</v-alert>
                    </template>
                </v-infinite-scroll>

                <DeleteGalery
                    v-model="deleteGalery"
                    :editedItem="this.editedItem"
                    @deleteConfirm="deleteGaleryConfirm"
                    @closeDelete="closeGaleryDialog"      
                />
            </v-row>
        </v-sheet>
    </v-container>
   

</template>

<script>
import axios from 'axios';
import Dashboard from '../../Auth/Dashboard.vue';
import ProductImageList from './partials/productImageList.vue';
import DeleteGalery from './partials/DeleteGalery.vue';
import { toHandlers } from 'vue';
export default {
    components: {
        Dashboard,
        ProductImageList,
        DeleteGalery
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
            const uniqueNames = [...new Set(this.images.map(
                item => item.product_name,
                productId => item.product_id
            ))];
            return uniqueNames.map(name => name.trim(), productId => productId);
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
            // this.editedIndex = this.images.indexOf(image);
            this.editedItem = image;
            this.deleteGalery = true;
        },
        deleteGaleryConfirm() {
            alert('delete Galery');
            // const token = document.head.querySelector('meta[name="csrf-token"]');
            // axios.delete(`/api/images/deleteAll/${this.editedItem.product_id}`)
            //     .then((response) => {
            //         this.images.splice(this.editedItem);
            //         return true;
            //     })
            //     .catch((response) => {
            //         alert('Error:' + response);
            //     });
            // this.closeGaleryDialog();

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