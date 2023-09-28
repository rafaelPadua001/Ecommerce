<template>
    <section>
        <Dashboard />
    </section>

    <v-container>
        <v-row no-gutters>
            <v-col v-for="(image, index) in images" :key="index">
                    <v-sheet class="ma-2 pa-2">
                        <div>
                            <v-card class="mx-auto" width="1500" max-width="300">
                                <v-img :src="'storage/products/' + image.name + '.' + image.extension"
                                    :lazy-src="'./storage/products/' + image.name + '.' + image.extension" class="align-end"
                                    gradient="to bottom, rgba(0, 0, 0, .1), rgba(0,0,0,.5)" height="250px" cover>

                                </v-img>
                                <v-card-text>

                                </v-card-text>

                                <v-card-actions>
                                    <v-btn>
                                        <v-icon icon="fa-solid fa-trash fa-2xs" color="red"
                                            @click="deleteItem(image)"></v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-card>

                            <v-dialog v-model="dialogDelete" max-width="500px">
                                <v-card>
                                    <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
                                        <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                                        <v-spacer></v-spacer>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
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
                })
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
        this.getImages();
    }
}
</script>