<template>
  <v-row>
    <v-col class="d-flex justify-center flex-column" cols="auto">
      <Dashboard />
    </v-col>
  </v-row>
  <v-row fluid>
    <v-col class="d-flex justify-center flex-column" cols="auto">
       <v-data-table
                  :headers="headers"
                  :items="subcategories"
                  :sort-by="[{ key: 'name', order: 'desc' }]"
                  class="elevation-1"
                >
                  <template v-slot:top>
                    <v-toolbar
                      class="bg-transparent"
                      flat
                    >
                      <v-toolbar-title>Subcategories</v-toolbar-title>
                      <v-divider
                        class="mx-4"
                        inset
                        vertical
                      ></v-divider>
                      <v-spacer></v-spacer>
                      <v-dialog
                        v-model="dialog"
                        max-width="900"
                      >
                        <template v-slot:activator="{ props }">
                          <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="props"
                          >
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
                                <v-row>
                                    <v-col>
                                        <h5>Category</h5>
                                    </v-col>
                                </v-row>
                                
                                <v-divider></v-divider>
                                <v-spacer></v-spacer>

                              <v-row>
                                <v-col
                                  cols="12"
                                  sm="6"
                                  md="4"
                                >
                                <v-select 
                                    v-model="this.editedItem.category_id"
                                    label="Category"
                                    :items="categories"
                                    item-title="name"
                                    item-value="id"
                                    
                                    >
                                   
                                </v-select>
                                </v-col>
                                
                              </v-row>
                              <v-row>
                                <v-col>
                                    <h5>Subcategory</h5>
                                </v-col>
                              </v-row>
                              
                              <v-divider></v-divider>
                              <v-spacer></v-spacer>

                              <v-row>
                                <v-col
                                  cols="12"
                                  sm="6"
                                  md="4"
                                >
                                  <v-text-field
                                    v-model="this.editedItem.name"
                                    label="Name subcategory"
                                    placeholder="Iphone, Xiamo, Samsung"
                                  ></v-text-field>
                                </v-col>
                              </v-row>
                            </v-container>
                          </v-card-text>
                         
                          <v-spacer></v-spacer>
                            <v-divider></v-divider>

                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                              color="blue-darken-1"
                              variant="text"
                              @click="close"
                            >
                              Cancel
                            </v-btn>
                            <v-btn
                              color="blue-darken-1"
                              variant="text"
                              @click="save"
                            >
                              Save
                            </v-btn>
                          </v-card-actions>
                        </v-card>
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
                  <template v-slot:item.created_at="{item}">
                    {{ item.created_at.split('T')[0] }}
                  </template>
                  <template v-slot:item.updated_at="{item}">
                    {{ item.updated_at.split('T')[0] }}
                  </template>
                  <template v-slot:item.actions="{ item }">
                    <v-btn-group>
                      <v-btn icon variant="plain"  @click="editItem(item)">
                      <v-icon
                          size="small"
                          class="me-2"
                        
                          icon="fas fa-regular fa-pen-to-square fa-2xs"
                      >
                        
                      </v-icon>
                    </v-btn>
                    <v-btn icon variant="plain" @click="deleteItem(item)">
                      <v-icon
                        size="small"
                        icon="fas fa-trash fa-2xs"
                      >
                      
                      </v-icon>
                    </v-btn>
                    </v-btn-group>
                    
                    
                  </template>
                  <template v-slot:no-data>
                    <v-btn
                      color="primary"
                      @click="initialize"
                    >
                      Reset
                    </v-btn>

                   
                  </template>
                </v-data-table>
      
    </v-col>
  </v-row>
    
</template> 

<script>
import axios from 'axios'
import Dashboard from '../Auth/Dashboard.vue';

export default {
  components: {
    Dashboard
  },
  data: () => ({
    dialog: false,
    dialogDelete: false,
    name: '',
    user: null,
    categories: '',
    selectedCategory: [],
    subcategories: [],
    headers: [
      {
        title: 'Name',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'Category', key: 'category_name' },
     // { title: 'Creator', key: 'user_name' },
      { title: 'created_at', key: 'created_at' },
      { title: 'updated_at', key: 'updated_at' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      category_id: '',
    },
    defaultItem: {
      name: '',
      category_id: '',
    },
  }),
computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
  },
watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },
created () {
    this.initialize()
  },
methods: {
    initialize () {
        this.categories = [],
        this.subcategories = []
    },
    getAuth() {
      axios.get('/users')
        .then((response) => {
          this.user = response.data;
          return true;
        })
        .catch((response) => {
          console.log(response);
          return false;
        });
    },
    getCategories(){
        axios.get('/categories')
        .then((response) => {
           return this.categories = response.data;
            console.log(this.categories);
            return true;
        })
        .catch((response) => {
            alert('Error: ' . response);
            return false;
        });
    },
    getSubcategories(){
        axios.get('/subcategories')
        .then((response) => {
           return this.subcategories = response.data;
            console.log(this.subcategories);
            return true;
        })
        .catch((response) => {
            alert('Error: ' . response);
            return false;
        });
    },
    editItem (item) {
      this.editedIndex = this.subcategories.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      this.editedIndex = this.subcategories.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    deleteItemConfirm () {
      axios.delete(`/api/subcategories/delete/${this.editedItem.id}`)
      .then((response) => {
        return true;
      })
      .catch((response) => {
        alert('Error: ' . response);
      });
      this.subcategories.splice(this.editedIndex, 1);
      this.closeDelete()
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save () {
      if (this.editedIndex > -1) {
        const data = {
          name: this.editedItem.name,
          user_id: this.editedItem.user_id,
          category_id: this.editedItem.category_id
        };
        axios.post(`/api/subcategories/update/${this.editedItem.id}`, data)
        .then((response) => {
          alert(response);
           return this.editedItem = response.data;
         })
        .catch((response) => {
          console.log(response)
          alert('Error: ' + response.error);
          return false;
        });
        Object.assign(this.subcategories[this.editedIndex], this.editedItem);
      } else {
        const data = {
          category_id: this.editedItem.category_id,
          user_id: this.editedItem.user_id,
          name: this.editedItem.name
        };
        axios.post(`/api/subcategories/store/${this.user.id}`, data)
        .then((response) => {
          return   this.subcategories.push(response.data);
        
          
        }).catch((response) => {
            alert('Error: ' . response);
        });
       // this.subcategories.push(this.editedItem);
      }
      this.close()
    },
  },
  mounted() {
    this.getAuth();
    this.getCategories();
    this.getSubcategories();
  }
}
</script>