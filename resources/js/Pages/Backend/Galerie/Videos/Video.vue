<template>
    <div>
        <Dashboard />
    </div>
    <div v-if="!videos">
        <v-card>
            <v-card-text>Nenhum Registro encontrado</v-card-text>
        </v-card>
    </div>
    <div v-else>
        <v-row fluid>
            <v-col 
                class="d-flex mx-auto justify-center mb-6 flex-column"
                cols="12"
                md="8"
                sm="4"
            >
                <v-card>
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
                                    <div>
                                     
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
                                    </div>
                                    
                                </v-toolbar>
                            </template>

                            <template v-slot:item.link="{item}">
                               <v-responsive 
                                    :aspect-ratio="16/9"
                                >
                                <iframe 
                                    :src="getEmbedUrl(item.link)"
                                    :max-width="50"
                                    frameborder="0"
                                    allowfullscreen
                                >
                                </iframe>
                                </v-responsive>
                            </template>
                            
                            <template v-slot:item.actions="{ item }">
                                <v-btn-group>
                                    <v-btn 
                                        icon 
                                        variant="plain"
                                        class="me-2"
                                        size="small"
                                        @click="deleteItem(item)"
                                    >
                                        <v-icon icon="fas fa-trash fa-2xs"></v-icon>
                                    </v-btn>
                                </v-btn-group>
                                
                            </template>
                            <template v-slot:no-data>
                                <v-btn color="primary" @click="initialize">
                                    Reset
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

    </div>
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
        dialogEdit: false,
        headers: [
            {
                title: 'Name',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            {title: 'product Name', key: 'product_name'},
            { title: 'Link', key: 'link' },
            { title: 'Plataform', key: 'plataform' },
            { title: 'product_id', key: 'product_id' },
            { title: 'user_id', key: 'user_id' },
            { title: 'Created', key: 'created_at' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        editedIndex: -1,
        editedItem: {},
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
                });
        },
        getEmbedUrl(link) {
            let videoId = link.split('v=')[1];
            const ampersandPosition = videoId.indexOf('&');
            if (ampersandPosition !== -1) {
                videoId = videoId.substring(0, ampersandPosition);
            }
            // Cria a URL de incorporação usando o ID do vídeo
            return `https://www.youtube.com/embed/${videoId}`;
        },
        deleteItem(item) {
            this.editedIndex = this.videos.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        deleteItemConfirm() {
            axios.delete(`/api/videos/delete/${this.editedItem.id}`)
                .then((response) => {
                    this.videos.splice(this.editedIndex, 1);
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