<template>
    <div>
        <Dashboard />
    </div>

    <v-container>
        <div v-if="!videos">
            <v-card>
                <v-card-text>Nenhum Registro encontrado</v-card-text>
            </v-card>
        </div>
        <v-card width="900">
            <v-card-text>
                <v-data-table :headers="headers" :items="videos" :sort-by="[{ key: 'id', order: 'desc' }]"
                    class="elevation-1">
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>Videos</v-toolbar-title>
                            <v-divider class="mx-4" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="500px">
                                <template v-slot:activator="{ props }">
                                    <v-btn color="primary" dark class="mb-2" v-bind="props" disabled>
                                        New Item
                                    </v-btn>
                                </template>
                               
                            </v-dialog>
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
                        </v-toolbar>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <a @click="editItem(item.raw)">
                            edit
                        </a>
                        <v-icon size="small" class="me-2" @click="editItem(item.raw)" icon="fas fa-pencil">
                            Edit
                        </v-icon>
                        <a @click="deleteItem(item.raw)">
                            remove
                        </a>
                        <v-icon size="small" @click="deleteItem(item.raw)" icon="fas fa-delete">
                            Remove
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="initialize">
                            Reset
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
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
        videos: [],
        dialogDelete: false,
        headers: [
            {
                title: 'Name',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            { title: 'Link', key: 'link' },
            { title: 'Plataform', key: 'link' },
            { title: 'Created', key: 'created_at' },
            { title: 'product_id', key: 'product_id' },
            { title: 'user_id', key: 'user_id' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
    }),
    watch: {
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    methods: {
        getVideos() {
            axios.get('/videos')
                .then((response) => {
                    return this.videos = response.data;
                })
                .catch((response) => {
                    alert('Error :' + response);
                })
        },
        deleteItem(item) {
            this.editedIndex = this.categories.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        deleteItemConfirm() {
            const itemId = this.editedIndex;

            axios.delete(`/api/categories/delete/${itemId}`)
                .then((response) => {
                    this.categories.splice(this.editedIndex, 1);
                })
                .catch((response) => {
                    alert('Error: ' + response);
                });

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
    mounted() {
        this.getVideos();
    }

}
</script>