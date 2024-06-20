<template>
    <div>
        <Dashboard />
    </div>

    <div v-if="carts.length == 0">
        <h5>Você ainda não possui itens em seu carrinho</h5>
    </div>
    <div v-else>
        <v-data-table :headers="headers" :items="carts" :sort-by="[{ key: 'id', order: 'desc' }]" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Cart Itens</v-toolbar-title>
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
            <template v-slot:item.images="{ item }">
                <v-row>
                    <v-col cols="12"  v-for="(imageName, index) in JSON.parse(item.images)" :key="index">
                        <v-img v-if="index == 0" :src="`./storage/products/${imageName}`" :width="100" :height="90" cover></v-img>
                    </v-col>
                </v-row>
            </template>
            <template v-slot:item.color="{ item }">
                <v-row>
                    <v-col>
                        <v-avatar
                            v-for="(color, index) in JSON.parse(item.cart_item_colors)" :key="index"
                            :color="color"
                            size="x-small"
                        >
                          
                        </v-avatar>
                      
                    </v-col>
                   
                </v-row>
            </template>
            <template v-slot:item.size="{ item }">
                
                <v-row>
                    <v-col>
                        <v-card
                            v-for="(color, index) in JSON.parse(item.cart_item_size)"
                            :key="index"
                            
                        >
                        {{ item.cart_item_size }}
                        </v-card>
                      
                    </v-col>
                   
                </v-row>
            </template>
            <template v-slot:item.price="{ item }">
                
                <v-row>
                    <v-col >
                      R$ {{ item.cart_item_price }}
                    </v-col>
                </v-row>
            </template>
            <!-- <template v-slot:item.created_at="{ item }">
                
                <v-row>
                    <v-col >
                      R$ {{ item.created_at }}
                    </v-col>
                </v-row>
            </template> -->
            <template v-slot:item.actions="{ item }">
                <v-btn-group>
                    <v-btn icon @click="buy(item)" size="x-small">
                        <v-icon class="me-2" icon="fa-solid fa-cart-shopping fa-2xs">

                        </v-icon>
                    </v-btn>


                    <v-btn icon size="x-small" @click="deleteItem(item)">
                        <v-icon @click="deleteItem(item.raw)" icon="fa-solid fa-trash fa-2xs">

                        </v-icon>
                    </v-btn>
                </v-btn-group>


            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">
                    Reset
                </v-btn>
            </template>
        </v-data-table>

        <div class="text-center">
          <v-snackbar v-model="snackbar" :timeout="3500" color="cyan-darken-3" vertical>

            <div class="text-subtitle-1 pb-2">{{this.message}}
            </div>
            <template v-slot:actions>
              <v-btn-group>
                <v-btn size="small" variant="plain" color="white" @click="snackbar = false">Close</v-btn>
                <v-btn size="small" variant="plain" color="white" :to="`/login`">Login</v-btn>
              </v-btn-group>
            </template>

          </v-snackbar>
        </div>
    </div>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';

export default {
    components: { Dashboard },
    data: () => ({
        carts: [],
        selectCart: false,
        dialog: false,
        dialogDelete: false,
        cartIndex: -1,
        snackbar: false,
        message: '',
        headers: [

            { title: 'image', value: 'images', key: 'images' },
            {
                title: 'Name',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            { title: 'colors',value: 'color', key: 'color' },
            { title: 'quantity',  key: 'quantity' },
            { title: 'sizes', key: 'size' },
            { title: 'price', key: 'price' },
            { title: 'Created', key: 'created_at' },
            { title: 'Updated', key: 'updated_at' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],

    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },
    watch: {

        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    methods: {
        initialize() {
            this.categories = [];
        },
        getCart() {
            axios.get('/carts')
                .then((response) => {

                    return this.carts = response.data;
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
        },
        deleteItem(item) {
            this.editedIndex = this.carts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        deleteItemConfirm() {
            console.log(this.editedItem);
            const itemId = this.editedItem.id;

            axios.delete(`/cartItem/delete/${itemId}`)
                .then((response) => {
                    this.snackbar = true;
                    this.message = 'item removed';
                    return this.carts.splice(this.editedIndex, 1);
                })
                .catch((response) => {
                    this.snackbar = true;
                    this.message = 'Erro on remove item';
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
        buy(item) {
            this.cartIndex = this.carts.indexOf(item);
            this.selectCart = Object.assign({}, item);
            console.log(this.cartIndex);
            console.log(this.selectCart);
            this.$router.push(`/carts/buy/${this.selectCart.id}`);

        },
    },
    created() {
        this.initialize();
        this.getCart();
    }
}
</script>
