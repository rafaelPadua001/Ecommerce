<template>
  <v-container>
    <v-sheet class="px-2 py-2">
      <v-row fluid no-gutters>
        <v-col class="d-flex justify-center flex-column" cols="auto">
          <Dashboard />
        </v-col>
      </v-row>

      <v-row justify="center" no-gutters>
        <v-col class="d-flex justify-center flex-column" cols="12" md="8" sm="6">
          <v-data-table :headers="headers" :items="categories" :sort-by="[{ key: 'id', order: 'desc' }]"
            class="elevation-1">
            <template v-slot:top>
              <v-toolbar flat class="bg-transparent">
                <v-toolbar-title>Categories</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="600">
                  <template v-slot:activator="{ props }">
                    <v-btn color="primary" dark class="mb-2" v-bind="props">
                      New Item
                    </v-btn>
                  </template>
                  <v-card>
                    <v-card-title>
                     
                      <v-toolbar class="bg-transparent">
                        <v-toolbar-title>
                          <span class="text-h5">{{ formTitle }}</span>
                        </v-toolbar-title>
                        <template v-slot:append>
                        <v-btn @click="dialog = false">
                          <v-icon icon="fas fa-close"></v-icon>
                        </v-btn>
                      </template>
                      </v-toolbar>
                      
                    </v-card-title>

                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="8" md="6">
                            <v-text-field v-model="editedItem.name" label="Category name"
                              placeholder="Eletronics"></v-text-field>
                          </v-col>

                        </v-row>

                        <v-row>
                          <v-col cols="12" sm="12" md="12">
                            <v-combobox v-model="icons" :items="itemsIcons" label="Select icon to category">
                              <template v-slot:selection="data">

                                <v-chip :key="JSON.stringify(data.item)" v-bind="data.attrs"
                                  :model-value="data.selected" :disabled="data.disabled" size="small"
                                  @click:close="data.parent.selectItem(data.item)">

                                  <template v-slot:prepend>
                                    <v-avatar class="bg-accent text-uppercase" start>
                                      <v-icon :icon="data.item.raw.icon"></v-icon>

                                    </v-avatar>
                                  </template>
                                  {{ data.item.title }}
                                </v-chip>
                              </template>
                            </v-combobox>

                            Icon selected: <v-icon :icon="this.editedItem.icon"></v-icon>
                          </v-col>
                        </v-row>

                        <v-row>
                          <v-col>
                            
                            <v-file-input v-model="thumbnail" label="thumbnail category" clearable @change="previewThumbnail">

                            </v-file-input>
                            preview thumbnail:
                            <div v-if="previewThumbnail && !editedItem.thumbnail">
                              <v-row v-if="thumbnail">
                               <v-col >
                                  <v-card v-for="(img, index) in thumbnail" :key="index">
                                    <v-img
                                      :src="img.src"
                                      :lazy-src="img.name"
                                      :alt="img.src"
                                      cover
                                    >

                                    </v-img>
                                  </v-card>
                                </v-col>  
                              </v-row>
                            </div>
                            <div v-else>
                            <v-row>
                               <v-col cols="12" md="6">
                                <v-card>
                                 {{ image }}
                                  <v-img 
                                      v-model="editedItem.thumbnail" 
                                      :src="'./storage/Categories/Thumbnails/' + editedItem.thumbnail"
                                      :lazy-src="image"
                                      :alt="'Image ' + index"
                                      cover
                                      
                                    >
                                   <div float="right" justify="center">
                                      <v-btn icon="fas fa-close" size="12px" density="compact" @click="removeImagePreviewEdit(index)">
                                        <v-icon icon="fas fa-close" size="10px"></v-icon>

                                      </v-btn>
                                    </div>
                                  
                                    </v-img>
                                    
                                </v-card>

                                
                              </v-col>
                              <v-divider></v-divider>
                              <v-spacer></v-spacer>
                              <v-spacer></v-spacer>
                            </v-row>
                           
                        </div>
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
            <template v-slot:item.thumbnail="{ item }">
              <v-row>
                <v-col>
                  <v-img :src="`./storage/Categories/Thumbnails/${item.thumbnail}`" :alt="`${item.thumbnail}`"
                    class="align-end text-white" :width="250" max-width="90" height="90" aspect-ratio="16/9">
                  </v-img>


                </v-col>
              </v-row>
            </template>
            <template v-slot:item.icon="{ item }">
              <v-icon size="x-small" :icon="item.icon"></v-icon>
            </template>
            <template v-slot:item.created_at="{ item }">
              {{ item.created_at.split('T')[0] }}
            </template>
            <template v-slot:item.updated_at="{ item }">
              {{ item.updated_at.split('T')[0] }}
            </template>
            <template v-slot:item.actions="{ item }">
              <v-btn-group>
                <v-btn icon variant="plain" @click="editItem(item)" color="primary">
                  <v-icon size="x-small" class="me-2" icon="fas fa-fa-regular fa-pen-to-square fa-2xs">

                  </v-icon>
                </v-btn>

                <v-btn icon variant="plain" @click="deleteItem(item)" color="error">
                  <v-icon size="x-small" icon="fas fa-trash fa-2xs">

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

        </v-col>
      </v-row>

    </v-sheet>
  </v-container>




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
    thumbnail: [],
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        title: 'Thumbnail',
        align: 'start',
        sortable: false,
        key: 'thumbnail'
      },
      {
        title: 'Name',
        key: 'name',
      },
      // { title: 'Creator', key: 'user_id' },
      { title: 'Icon', key: 'icon' },
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
    icons: [],
    itemsIcons: [
      {
        title: 'Smartphones',
        icon: 'fas fa-mobile-screen-button'
      },
      {
        title: 'Eletronics',
        icon: 'fas fa-plug'
      },
      {
        title: 'Clothing',
        icon: 'fas fa-shirt'
      },
      {
        title: 'Utensils',
        icon: 'fas fa-utensils'
      },
      {
        title: 'Cars',
        icon: 'fas fa-car'
      },
      {
        title: 'Games',
        icon: 'fas fa-gamepad'
      },
      {
        title: 'PET',
        icon: 'fas fa-paw'
      },

    ],
    editedIndex: -1,
    editedItem: [],
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
    previewThumbnail(newUrl, oldUrl) {
        // Executar lógica quando a imagem é alterada
        console.log(`A imagem foi alterada de ${oldUrl} para ${newUrl}`);
        return true;
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
      console.log(this.editedItem.id);
      axios.delete(`/api/categories/delete/${this.editedItem.id}`)
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
    previewThumbnail(event){
    
      const files = event.target.files;
    
      if(files){
       
        for(let i = 0; i < files.length; i ++){
          const reader = new FileReader();
          const file = files[i];
         
          reader.onload = (e) => {
            this.thumbnail.push({
              src: e.target.result,
              name: file.name
            });
          };
          reader.readAsDataURL(file);
        }
      }
    },
    save() {
      console.log(this.editedIndex);
      if (this.editedIndex > -1) {
        const token = document.head.querySelector('meta[name="csrf-token"]').content;

        const data = {
          name: this.editedItem.name,
          user_id: this.user.id,
          icon: this.icons.icon,
          thumbnail: this.thumbnail
        };
        axios.post(`/api/categories/update/${this.editedItem.id}`, data,
          {
            headers: {
              'X-CSRF-TOKEN': token,
              'Content-Type': 'multipart/form-data'
            }
          })
          .then((response) => {
           
            console.log(this.editedIndex);
            Object.assign(this.categories[this.editedIndex], response.data);
            return this.close();
          })
          .catch((response) => {
            return alert('Error:' + response);
          })
         // this.close();
         // Object.assign(this.categories[this.editedIndex], editedItem);
      }
      else {
        const token = document.head.querySelector('meta[name="csrf-token"]').content;
        const userId = this.user.id

        const data = { name: this.editedItem.name, icon: this.icons.icon, thumbnail: this.thumbnail };

        axios.post(`/api/categories/store/${this.user.id}`, data, {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'multipart/form-data'
          }
        })
          .then((response) => {
            this.categories.push(response.data.original);
            return this.close();
          })
          .catch((response) => {
            alert('Error' + response);
          })
        //  this.desserts.push(this.editedItem);
          
      }
      //this.close()
    },
  },
  created() {
    this.getAuth();
    this.getCategories();
  }
}
</script>