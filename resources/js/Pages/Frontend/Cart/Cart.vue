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
            <template v-slot:item.actions="{ item }">
                <v-btn-group>
                    <v-btn icon  @click="buy(item)" size="x-small">
                    <v-icon class="me-2" icon="fa-solid fa-cart-shopping fa-2xs">
                  
                    </v-icon>
                </v-btn>
               
                
                <v-btn icon size="x-small"  @click="deleteItem(item.raw)">
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
        headers: [
            {
                title: 'Name',
                align: 'start',
                sortable: false,
                key: 'name',
            },
            { title: 'image', key: 'images' },
            { title: 'quantity', key: 'quantity' },
            { title: 'colors', key: 'color' },
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
      const itemId = this.editedIndex;

      axios.delete(`/api/categories/delete/${itemId}`)
        .then((response) => {
          this.carts.splice(this.editedIndex, 1);
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
    buy(item){
        this.cartIndex = this.carts.indexOf(item);
        this.selectCart = Object.assign({}, item);
        this.$router.push(`/carts/buy/${this.selectCart.value}`);
    
    },
   },
    created() {
        this.initialize();
        this.getCart();
    }
}
</script>
