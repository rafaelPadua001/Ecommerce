<template>
    <v-container>
       <v-row no-gutters>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <Dashboard />
                </v-col>
        </v-row>
        <v-row justify="center" no-gutters>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <v-card class="mx-auto" elevation="0">
                        <v-card-title>
                            <v-toolbar class="bg-transparent">
                                <template v-slot:prepend>
                                    <p class="text-h5">Deliveries</p>
                                </template>

                                <v-divider class="ms-3" inset vertical></v-divider>

                                <template v-slot:append>
                                    <v-btn-group>
                                        <v-btn class="mb-2" variant="plain" color="primary" @click="newItemDialog">
                                            new Item
                                        </v-btn>
                                    </v-btn-group>
                                </template>
                            </v-toolbar>
                        </v-card-title>

                        <v-card-text>
                            <v-row v-if="Object.keys(deliveries).length >= 1" fluid>
                                <v-col v-for="(delivery, index) in deliveries" :key="index"
                                    class="d-flex justify-center flex-column" cols="auto" md="8" sm="4">
                                    <v-card class="mx-auto" :width="400">
                                        <v-toolbar class="bg-transparent">
                                            <v-switch v-model="delivery.activated" label="activated ?"
                                                :value="activated[index]" :activator="'#switch-activator-' + index"
                                                @change="alterStatus(delivery)" color="success">
                                            </v-switch>


                                            <template v-slot:append>
                                                <v-btn-group :id="'switch-activator-' + index">

                                                    <v-btn icon variant="plain" :id="'menu-activator-' + index">
                                                        <v-icon icon="fas fa-ellipsis-vertical"></v-icon>
                                                    </v-btn>


                                                </v-btn-group>
                                            </template>

                                            <v-menu :activator="'#menu-activator-' + index">
                                                <v-list>
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

                                        <v-divider></v-divider>
                                       
                                        <v-card-text>
                                            <v-row>
                                                <v-col cols="auto">
                                                    <v-img :width="100"
                                                        :src="`./storage/delivery/${delivery.thumbnail}`"
                                                        :lazy-src="`./storage/delivery/${delivery.thumbnail}`"
                                                        :alt="delivery.thumbnail" cover aspect-ratio="4/9">

                                                    </v-img>
                                                </v-col>
                                                <v-col cols="auto">
                                                    <a :href="delivery.url">{{ delivery.name }}</a> 
                                                </v-col>

                                            </v-row>



                                        </v-card-text>

                                        <v-card-text>
                                            <v-row>
                                                <v-col>
                                                    Created: {{ delivery.created_at.split('T')[0] }}
                                                </v-col>
                                                <v-col>
                                                    updated: {{ delivery.updated_at.split('T')[0] }}
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>

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

                    <v-col>
                        <v-dialog v-model="createDialog" transition="dialog-bottom-slide" fullscreen>
                            <NewItem :createDialog="createDialog" @create="create" @close-dialog="closeDialog" />
                        </v-dialog>
                        <v-dialog v-model="removeDialog">
                            <RemoveItem :removeDialog="removeDialog" :delivery="this.editedItem" @remove="removeItem"
                                @close-dialog="closeRemoveDialog" />
                        </v-dialog>
                    </v-col>
                </v-col>
            </v-row>
 
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
                    return this.deliveries = response.data.map(delivery => ({
                        ...delivery,
                        activated: delivery.activated === 1,
                    }));
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
                status: item.activated ? 1 : 0,
            };
            axios.post(`/delivery/status/${item.id}`, data)
                .then((response) => {
                    const index = this.deliveries.findIndex(d => d.id === item.id);
                    if (index !== -1) {
                        return this.$set(this.deliveries, index, {
                            ...this.deliveries[index],
                            ...response.data,
                            activated: response.data.activated === 1
                        });
                    }
                    //   return Object.assign(this.deliveries[this.editedIndex], response.data);
                })
                .catch((response) => {
                    return console.log('Error:' + response);

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