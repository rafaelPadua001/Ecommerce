<template>
    <div>
        <Dashboard />
    </div>
    <v-row>
        <v-col>
            <v-sheet>
                <v-card>
                    <v-card-text>
                        <v-data-table :headers="headers" :items="orders" :sort-by="[{ key: 'id', order: 'desc' }]"
                            class="elevation-1">

                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Orders</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical>

                                    </v-divider>

                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialog" max-width="500px">
                                        <template v-slot:activator="{ props }">
                                            <v-btn color="prymary" dark class="mb-2" v-bind="props">
                                                New Item
                                            </v-btn>

                                        </template>

                                        <v-card>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field></v-text-field>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.color="{ item }">
                                <v-row>
                                    <v-col>
                                        <v-avatar v-for="(color, index) in JSON.parse(item.color)"
                                            :key="index" :color="color" size="x-small"
                                        >
                                        {{ color }}
                                        </v-avatar>

                                    </v-col>

                                </v-row>
                            </template>
                            <template v-slot:item.status="{ value }">
                                <v-chip :color="getColor(value)">
                                    {{ value }}
                                </v-chip>
                            </template>


                            <template v-slot:item.actions="{ item }">
                                <v-btn-group>
                                    <v-btn>
                                        <v-icon size="x-small" class="me-2" color="primary" @click="editItem(item)"
                                            icon="fas fa-truck-fast"></v-icon>

                                    </v-btn>
                                    <!--   <v-btn>
                                        <v-icon size="x-small" class="me-2" @click="deleteItem(item)" icon="fas fa-ticket"
                                            color="primary"></v-icon>
                                    </v-btn>
                                    <v-btn>
                                        <v-icon size="x-small" class="me-2" @click="deleteItem(item)" icon="fas fa-remove"
                                            color="primary"></v-icon>
                                    </v-btn>
                                    -->

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
            </v-sheet>
        </v-col>
    </v-row>
    <h1></h1>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';

export default {
    components: {
        Dashboard
    },
    data: () => ({
        orders: [],
        dialog: false,
        headers: [
            {
                title: 'id',
                align: 'start',
                key: 'id',
                sortable: false,
            },
            { title: 'client', key: 'client' },
            { title: 'description', key: 'description' },
            { title: 'color', key: 'color' },
            { title: 'Exec Date', key: 'exec_date' },
            { title: 'value', key: 'value' },
            { title: 'status', key: 'status' },
            { title: 'created', key: 'created_at' },
            { title: 'updated', key: 'updated_at' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        watch: {
            dialog(val) {
                val || this.close();
            }
        },
        created() {
            this.initialize();
        },
    }),
    methods: {
        initialize() {
            this.orders = [];
        },
        getOrders() {
            axios.get('/orders')
                .then((response) => {
                    return this.orders = response.data;
                })
                .catch((response) => {
                    return alert('Error: ', response);
                })
        },
        getColor(status) {
            if (status == 'open') return 'red';
            else if (status == 'close') return 'green';
        },
        editItem() {
            alert('teste');
        },
        deleteItem() {
            alert('teste');
        }
    },
    mounted() {
        this.getOrders();
    },
}
</script>