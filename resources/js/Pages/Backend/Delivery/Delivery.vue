<template>
    <v-container>
        <v-sheet class="px-2 py-2">
            <v-row>
                <v-col>
                    <Dashboard />
                </v-col>
            </v-row>

            <v-row>
                <v-col class="d-flex justify-center flex-column">
                    <v-card class="mx-auto" :max-width="800">
                        <v-card-title>
                            <v-toolbar>
                                <template v-slot:prepend>
                                    <p class="text-h5">Deliveries</p>
                                </template>

                                <v-divider class="ms-3" inset vertical></v-divider>

                                <template v-slot:append>
                                    <v-btn-group>
                                        <v-btn class="mb-2" variant="plain" color="primary" @click="newItemDialog">new
                                            Item</v-btn>
                                    </v-btn-group>
                                </template>
                            </v-toolbar>
                        </v-card-title>



                        <v-card-text>
                            <v-row v-if="Object.keys(deliveries).length >= 1">
                                <v-col v-for="(delivery, index) in deliveries" :key="index"
                                    class="d-flex justify-center flex-column" cols="12" md="6" sm="3">
                                    <v-sheet class="ma-2 pa-2">
                                        <v-card class="mx-auto">
                                            <v-toolbar v-slot:append>
                                                <v-btn-group :id="'switch-activator-' + index">
                                                    <v-switch v-model="delivery.activated" label="activated ?"
                                                        :value="activated[index]"
                                                        :activator="'#switch-activator-' + index"
                                                        @change="alterStatus(delivery)"
                                                    >
                                                    </v-switch>


                                                    {{ delivery.activated }}
                                                </v-btn-group>
                                                <v-btn icon variant="plain" :id="'menu-activator-' + index">
                                                    <v-icon icon="fas fa-ellipsis-vertical"></v-icon>
                                                </v-btn>

                                                <v-menu :activator="'#menu-activator-' + index">
                                                    <v-list>
                                                        <v-list-item-group></v-list-item-group>
                                                        <v-list-item>
                                                            <v-list-item-title>
                                                                <v-btn icon variant="plain" @click="remove(delivery)">
                                                                    <v-icon icon="fas fa-trash">

                                                                    </v-icon>

                                                                </v-btn>

                                                            </v-list-item-title>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>

                                            </v-toolbar>
                                            <v-card-text>
                                                <v-img :src="`./storage/delivery/${delivery.thumbnail}`"
                                                    :lazy-src="`./storage/delivery/${delivery.thumbnail}`" cover
                                                    aspect-ratio="4/9">

                                                </v-img>

                                            </v-card-text>
                                            <v-card-text>
                                                <v-row>
                                                    <v-col>
                                                        {{ delivery.name }}
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        {{ delivery.url }}
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        {{ delivery.created_at }}
                                                    </v-col>
                                                </v-row>


                                            </v-card-text>
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

                    <div>
                        <v-dialog v-model="createDialog" transition="dialog-bottom-slide" fullscreen>
                            <NewItem :createDialog="createDialog" @create="create" @close-dialog="closeDialog" />
                        </v-dialog>
                        <v-dialog v-model="removeDialog">
                            <RemoveItem :removeDialog="removeDialog" :delivery="this.editedItem" @remove="removeItem"
                                @close-dialog="closeRemoveDialog" />
                        </v-dialog>
                    </div>


                </v-col>
            </v-row>


        </v-sheet>

    </v-container>
</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import NewItem from './Partials/NewItem.vue';
import RemoveItem from './Partials/RemoveItem.vue';

export default {
    components: {
        Dashboard,
        NewItem,
        RemoveItem
    },
    emits: ['create', 'remove', 'close-dialog'],
    data: () => ({
        deliveries: [],
        activated: true,
        createDialog: false,
        removeDialog: false,
        editedIndex: -1,
        editedItem: false,
    }),
    methods: {
        getDelivery() {
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
        create(response) {
            return this.deliveries.push(response.data);
        },
        closeDialog() {
            this.createDialog = false;
        },
        alterStatus(item) {

            const data = {
                status: item.activated,
            };
            axios.post(`/delivery/status/${item.id}`, data)
                .then((response) => {
                    return Object.assign(this.deliveries[this.editedIndex], response.data);
                })
                .catch((response) => {
                    return alert('Error:' + response);
                });
        },
        remove(item) {
            this.editedIndex = this.deliveries.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.removeDialog = true;
        },
        closeRemoveDialog() {
            this.removeDialog = false;
        },
        removeItem() {
            this.closeRemoveDialog();
            return this.deliveries.splice(this.editedIndex, 1);
        }

    },
    created() {
        this.getDelivery();
    }
}
</script>