<template>
  <div>
    <Dashboard />
  </div>
  <div>
    <div align="left">
      <v-breadcrumbs :items="items">
        <template v-slot:title="{ item }">
          {{ item.title.toUpperCase() }}
        </template>
      </v-breadcrumbs>
    </div>

    <v-card width="900">

      <v-divider></v-divider>
      <v-card-text>

        <v-data-table :headers="headers" :items="categories" :sort-by="[{ key: 'id', order: 'desc' }]"
          class="elevation-1">
          <template v-slot:top>
            <v-toolbar flat>
              <v-toolbar-title>Categories</v-toolbar-title>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-dialog v-model="dialog" max-width="500px">
                <template v-slot:activator="{ props }">
                  <v-btn color="primary" dark class="mb-2" v-bind="props">
                    New Item
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                  </v-card-title>

                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="8" md="6">
                          <v-text-field v-model="editedItem.name" label="Category name"
                            placeholder="Eletronics"></v-text-field>
                        </v-col>

                      </v-row>
                    </v-container>
                  </v-card-text>

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

  </div>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';

export default {
  components: {
    Dashboard
  },
  data: () => ({
    user: '',
    categories: [],
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        title: 'Name',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'Creator', key: 'user_name' },
      { title: 'Created', key: 'created_at' },
      { title: 'Updated', key: 'updated_at' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    items: [
      {
        title: 'Dashboard',
        disabled: false,
        href: '/Dashboard',
      },
      {
        title: 'Categories',
        disabled: false,
        href: 'breadcrumbs_link_1',
      },
      {
        title: 'Link 2',
        disabled: true,
        href: 'breadcrumbs_link_2',
      },
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      created: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: '',
      created: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
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
    this.initialize()
  },

  methods: {
    initialize() {
      this.categories = [];
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
    getCategories() {
      axios.get('/categories')
        .then((response) => {
          console.log(response);
          this.categories = response.data;
          return true;
        })
        .catch((response) => {
          console.log('Error:'.response);
          return false;
        });
    },
    editItem(item) {
      this.editedIndex = this.categories.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
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
        const token = document.head.querySelector('meta[name="csrf-token"]');
       
        const data = { name: this.editedItem.name, user_id: this.user.id };
        axios.post(`/api/categories/update/${this.editedItem.id}`, data,
          {
            headers: {
              'X-CSRF-TOKEN': token
            }
          })
          .then((response) => {
            return true;
          })
          .catch((response) => {
            alert('Error:'.response.error);
          })
        Object.assign(this.categories[this.editedIndex], this.editedItem);

      } else {
        const token = document.head.querySelector('meta[name="csrf-token"]');
        const userId = this.user.id

        const data = { name: this.editedItem.name };
        axios.post(`/api/categories/store/${this.user.id}`, data, {
          headers: {
            'X-CSRF-TOKEN': token
          }
        })
          .then((response) => {
            this.categories.push(response.data);
          })
          .catch((response) => {
            alert('Error' + response);
          })
        //  this.desserts.push(this.editedItem);

      }
      this.close()
    },
  },
  created() {
    this.getAuth();
    this.getCategories();
  }
}
</script>