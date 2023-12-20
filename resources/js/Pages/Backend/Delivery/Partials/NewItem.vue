<template>
    <v-row>
        <v-col class="d-flex justify-center flex-column">
            <v-sheet>
                <v-card>
                    <v-card-title>
                        <v-toolbar>
                            New Item
                            <template v-slot:append>
                                <v-btn icon variant="plain" @click="close">
                                    <v-icon icon="fas fa-close">

                                    </v-icon>
                                </v-btn>
                            </template>
                        </v-toolbar>
                    </v-card-title>
                    <v-card-text>
                        <v-sheet>
                            <v-row>
                                <v-col cols="12" md="6" sm="3">
                                    <v-text-field v-model="name" label="Enterprise name" :rules="nameRules"
                                        required></v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" md="6" sm="3">
                                    <v-text-field v-model="url" label="Enterprise Url" :rules="urlRules"
                                        required></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6" sm="3">
                                    <v-file-input label="File input" v-model="thumbnail" @change="previewImage" chips>
                                    </v-file-input>
                                   
                                </v-col>
                                <v-col>
                                    <div v-if="previewImage">
                            <v-row v-if="thumbnail">
                              <v-col v-for="(image, index) in thumbnail" :key="index" cols="12" md="6">
                                <v-card>
                                  <v-img 
                                      v-model="images"
                                      :src="image.src" 
                                      :lazy-src="image.name"
                                      :alt="'Image ' + index"
                                      cover
                                      
                                    >
                                    <div float="right" justify="center">
                                      <v-btn icon="fas fa-close" size="12px" density="compact" @click="removeImagePreview">
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
                            <v-row v-if="thumbnail.length == 0">
                            <v-col v-for="(imageName, index) in thumbnail" :key="index">
                              <v-img :src="`./storage/products/${imageName}`" max-width="100" max-height="100"></v-img>
                            </v-col>
                          </v-row>
                          </div>

                          <div v-else>
                            <v-row v-if="!previewImages">
                         
                              <v-col v-for="(image, index) in thumbnail" :key="index" cols="12" md="6">
                                <v-card>
                                 
                                  <v-img 
                                      v-model="editedItem.image" 
                                      :src="'./storage/uploads/' + image"
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
                            <v-row>
                                <v-col cols="12" md="6" sm="3">
                                    <v-switch v-model="activated" label="Activated ?"></v-switch>
                                </v-col>
                            </v-row>
                        </v-sheet>

                    </v-card-text>

                    <v-card-actions>
                        <v-btn-group>
                            <v-btn variant="plain" @click="save">Save</v-btn>
                            <v-btn variant="plain">Close</v-btn>
                        </v-btn-group>
                    </v-card-actions>
                </v-card>
            </v-sheet>

        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['createDialog','deliveries'],
    data: () => ({
        //deliveries: [],
        name: '',
        nameRules: [
            v => !!v || 'Enterprise name is required',
            v => (v && v.length <= 20) || 'The enterprise name has more than twenty characters.',
        ],
        url: '',
        urlRules: [
            v => !!v || 'Url is required',
            v => (v && v.length <= 100) || 'The enterprise name has more than a hundred characters.',
        ],
        thumbnail: false,
        activated: false,
    }),
    watch: {
        close(val){
            val || this.close();
        },
        previewImage(newUrl, oldUrl) {
            // Executar lógica quando a imagem é alterada
            console.log(`A imagem foi alterada de ${oldUrl} para ${newUrl}`);
            return true;
        },
    },
    methods: {
        close() {
            this.$emit('close-dialog');
        },
        handleFiles() {
            this.$refs.thumbnail[0];
        },
        previewImage(event) {
            const files = event.target.files;
            if (files) {
                this.thumbnail = [];
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    const file = files[i];

                    reader.onload = (e) => {
                        this.thumbnail.push({
                            src: e.target.result,
                            name: file.name,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }
        }, 
        removeImagePreview(index){
            return this.thumbnail.splice(index, 1);
        },
        save(){
            const token = document.head.querySelector('meta[name="csrf-token"]').content;
            const data = {
                name: this.name,
                url: this.url,
                thumbnail: this.thumbnail,
                activated: this.activated
            };
            axios.post(`/delivery/insert`, data, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                this.$emit('close-dialog');
                return this.$emit('create', response);
                
            })
            .catch((response) => {
                return alert('Error :' + response);
            });

           
        }

    }
}
</script>