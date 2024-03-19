<template>
  <v-row>
    <v-col class="d-flex justify-center flex-column" cols="auto">
      <Dashboard />
    </v-col>
  </v-row>
    <v-data-table :headers="headers" :items="seo" :sort-by="[{ key: 'id', order: 'asc' }]" >
              <template v-slot:top>
                <v-toolbar 
                  class="bg-transparent"
                  flat>
                  <v-toolbar-title>Product SEO</v-toolbar-title>
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
                              <v-text-field v-model="editedItem.meta_name" label="Meta Name"></v-text-field>
                            </v-col>

                            <v-col cols="12" sm="6" md="4">
                              <v-text-field v-model="editedItem.meta_keyword" label="Meta Key"></v-text-field>
                            </v-col>
                          </v-row>
                          <v-row>

                            <v-col cols="12" sm="6" md="4">
                              <v-textarea clearable v-model="editedItem.meta_description" label="Meta Description"
                                prepend-icon="$vuetify" variant="solo-filled"></v-textarea>
                            </v-col>

                            <v-col cols="12" sm="6" md="4">
                              <v-text-field v-model="editedItem.slug" label="Slug"></v-text-field>
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
                  <v-btn icon variant="plain"  @click="editItem(item)" size="small">
                  <v-icon size="small" class="me-2" icon="fa-regular fa-pen-to-square">
                
                 </v-icon>
                </v-btn>
                <v-btn icon variant="plain" @click="deleteItem(item)" size="small">
                  <v-icon   icon="fas fa-remove fa-2xs">
                
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

</template>

<script>


import axios from 'axios';
import Dashboard from '../../Auth/Dashboard.vue';


export default {
  components: { Dashboard },
  data: () => ({
    users: [],
    seo: [],
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        title: 'Product',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'name', key: 'meta_name' },

      { title: 'keyword', key: 'meta_keyword' },
      { title: 'description', key: 'meta_description' },
      { title: 'slug', key: 'slug' },
    //  { title: 'product_id', key: 'product_id' },
 //     { title: 'user_id', key: 'user_id' },
      { title: 'created', key: 'created_at' },
      { title: 'updated', key: 'updated_at' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      meta_name: '',
      meta_keyword: '',
      meta_description: '',
      slug: '',
    },
    defaultItem: {
      name: '',
      meta_name: '',
      meta_keyword: '',
      meta_description: '',
      slug: '',
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
    getAuth() {
      axios.get('/users')
        .then((response) => {
          this.users = response.data;
          return true;
        })
        .catch((response) => {
          console.log(response);
          return false;
        });
    },
    getSeo() {
      axios.get('/seo')
        .then((response) => {
          return this.seo = response.data;
        })
        .catch((response) => {
          return alert('Error: ' + response);
        });
    },
    editItem(item) {
      this.editedIndex = this.seo.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.editedIndex = this.seo.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    deleteItemConfirm() {
      axios.delete(`/api/seo_product/delete/${this.editedItem.id}`)
        .then((response) => {
         
          
          this.seo.splice(this.editedIndex, 1);
        })
        .catch((response) => {
          alert('Error:'.response);
          return false;
        });

    
      this.closeDelete()
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    save() {
      if (this.editedIndex > -1) {
        const data = {
          name: this.editedItem.name,
          meta_name: this.editedItem.meta_name,
          meta_keyword: this.editedItem.meta_keyword,
          meta_description: this.editedItem.meta_description,
          slug: this.editedItem.slug,
          user_id: this.users.id
        }

        axios.post(`/api/seo_product/update/${this.editedItem.id}`, data, {
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

        Object.assign(this.seo[this.editedIndex], this.editedItem)
      } else {

        const data = {
          name: this.editedItem.name,
          meta_name: this.editedItem.meta_name,
          meta_keyword: this.editedItem.meta_key,
          meta_description: this.editedItem.meta_description,
          slug: this.editedItem.slug,
          
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
    this.getAuth();
    this.getSeo();
    
  }


}
</script>