<template>
  <div>
    <Dashboard />
  </div>
  <v-card width="1500">
    <v-card-text>
      <v-data-table :headers="headers" :items="stocks" :sort-by="[{ key: 'id', order: 'asc' }]" class="elevation-1">
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Product Stock</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="900">
              <template v-slot:activator="{ props }">
                <v-btn color="primary" dark class="mb-2" v-bind="props" disabled>
                  New Item
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-text>
                  <v-container>
                    <h5>Seo </h5>
                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.quantity" label="Quantity"></v-text-field>
                      </v-col>
                     
                    </v-row>
                    <v-row>

                      <v-col cols="12" sm="6" md="4">
                            <h5>Select Colors:</h5>

                            <v-color-picker
                              v-model="editedItem.colors"
                              class="ma-2"
                              show-swatches
                              swatches-max-height="55px"
                              
                              width="90%"
                            >
                              
                            </v-color-picker>
                            <div>
                                <v-btn size="x-small" icon @click="selectedColor()">
                                  <v-icon icon="fas fa-save"></v-icon>
                                </v-btn>
                            </div>
                          
                        </v-col>
                        
                        <v-col cols="8" sm="4" md="2">
                          <v-card v-for="(color, index) in colors" :key="index" :color="color">
                            <template v-slot:append>
                                  <v-btn icon density="compact" size="small" @click="removeSelectedColor(index)">
                                    <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                  </v-btn>
                                </template>
                          </v-card>
                        </v-col>
                      <v-col>
                          <v-select
                            v-model="editedItem.size"
                            chips
                            label="Sizes"
                            :items="['P', 'M', 'G', 'GG', 'XG']"
                            multiple
                            variant="underlined"
                          ></v-select>
                        </v-col>

                    </v-row>


                  </v-container>
                </v-card-text>

                <v-spacer></v-spacer>
                <v-divider></v-divider>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-darken-1" variant="text" @click="close">
                    Cancel
                  </v-btn>
                  <v-btn color="blue-darken-1" variant="text" @click="save">
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
           <!--<v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
                  <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>-->
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon size="small" class="me-2" @click="editItem(item.raw)">
            mdi-pencil
          </v-icon>
          <!--<v-icon size="small" @click="deleteItem(item.raw)">
            mdi-delete
          </v-icon> -->
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">
            Reset
          </v-btn>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>
   
<script>
import axios from 'axios';
import Dashboard from '../../Auth/Dashboard.vue';


export default {
  components: { Dashboard },
  data: () => ({
    stocks: [],
    colors: [],
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        title: 'Name',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'product_colors', key: 'product_colors' },

      { title: 'product_size', key: 'product_size' },
      { title: 'quantity', key: 'stock_quantity' },
      { title: 'product_id', key: 'product_id' },
      { title: 'user_id', key: 'user_id' },
      { title: 'created', key: 'created_at' },
      { title: 'updated', key: 'updated_at' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      product_colors: '',
      size: '',
      quantity: '',
      
    },
    defaultItem: {
      name: '',
      product_colors: '',
      size: '',
      quantity: '',
    },
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
  },
  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },
  created() {
    this.initialize();

  },
  methods: {
    initialize() {
      this.seo = []
    },
    getStocks() {
      axios.get('/stocks')
        .then((response) => {
          return this.stocks = response.data;
        })
        .catch((response) => {
          alert('Error: ' + response);
        })
    },
    selectedColor(){
      let selected_colors = this.editedItem.colors;
      return this.colors.push(selected_colors);
    },
    removeSelectedColor(index){
      return this.colors.splice(index, 1);
    },
    editItem(item) {
    this.editedIndex = this.stocks.indexOf(item)
    this.editedItem = Object.assign({}, item)
    this.dialog = true
  },

 /* deleteItem(item) {
    this.editedIndex = this.stocks.indexOf(item)
    this.editedItem = Object.assign({}, item)
    this.dialogDelete = true
  },

  deleteItemConfirm() {
    axios.delete(`/api/seo/delete/${this.editedItem.id}`)
      .then((response) => {
        return true;
      })
      .catch((response) => {
        alert('Error:'.response);
        return false;
      });

    this.stocks.splice(this.editedIndex, 1)
    this.closeDelete()
  }, */

  close() {
    this.dialog = false
    this.$nextTick(() => {
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedIndex = -1
    })
  },

 /* closeDelete() {
    this.dialogDelete = false
    this.$nextTick(() => {
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedIndex = -1
    })
  }, */
  save() {
    if (this.editedIndex > -1) {
      const data = {
        name: this.editedItem.name,
        stock_quantity: this.editedItem.quantity,
        product_size: this.editedItem.size,
        product_colors: this.colors,
      }

      axios.post(`/api/stocks/update/${this.editedItem.id}`, data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
      )
        .then((response) => {
          this.editedItem = response.data;
          return true;
        })
        .catch((response) => {
          alert('Error: ' + response);
          return false;
        });

      Object.assign(this.stocks[this.editedIndex], this.editedItem)
    } else {

      const data = {
        name: this.editedItem.name,
        stock_quantity: this.editedItem.quantity,
        product_size: this.editedItem.size,
        product_colors: this.editedItem.colors
       
      };
      axios.post(`/api/products/store`, data,
        {
          headers: {
            'Content-Type': 'multipart/form-data'

          }
        }

      )
        .then((response) => {
          this.editedItem = response.data;
          console.log(response.data);
          return true;
        })
        .catch((response) => {
          alert('Error: '.response);
          return false;
        });

      this.products.push(this.editedItem);
    }
    this.close()
   },
  },
mounted() {
    this.getStocks();
  }
}
</script>