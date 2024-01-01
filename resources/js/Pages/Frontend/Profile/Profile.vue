<template>
  <div>
    <!-- <Dashboard /> -->
  </div>

  <v-container>
    <v-row no-gutters>
      <v-col cols="auto" md="12">
        <v-sheet class="ma-2 pa-2">
          <div>
            <v-container>
              <v-row no-gutters>
                <v-col cols="auto">
                  <v-sheet class="ma-1 pa-1">
                    <v-hover>
                      <template v-slot="{ isHovering, props }">
                        <v-card class="mx-auto" :max-width="734" v-bind="props" elevation="0">
                          <v-avatar color="grey" size="150">
                            <v-img color="surface-variant" cover
                              src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0s9szfX_siS_iDFcfPRqjxs0R1n9Qh2twgox6DHU-h1bBG9BtkBBVmmEWOGj35H3CIIU"
                              :alt="customer.name" v-if="Object.keys(profileImage).length == 0">

                              <v-expand-transition v-if="isHovering">
                                <template
                                  class="d-flex transition-fast-in-fast-out bg-grey-darken-4 v-card--reveal text-h5"
                                  v-if="Object.keys(profileImage).length == 0">
                                  <v-btn icon size="x-small" density="confortable" variant="elevated">
                                    <v-icon icon="fa-solid fa-plus fa-2xs" color="red-lighten-1"
                                      @click="uploadProfileImage"></v-icon>
                                  </v-btn>
                                </template>
                              </v-expand-transition>


                            </v-img>
                            <v-img cover v-if="Object.keys(profileImage).length >= 1"
                              :src="`/storage/avatars/${profileImage.name}`"
                              :lazy-src="`/storage/avatars/${profileImage.name}`">
                              <v-expand-transition>
                                <div v-if="isHovering"
                                  class="d-flex transition-fast-in-fast-out bg-grey-darken-4 v-card--reveal text-h5"
                                  style="height: 100%">

                                  <v-btn-group>
                                    <v-btn icon size="x-small" density="confortable" variant="plain">
                                      <v-icon icon="fas fa-trash fa-2xs" color="white"
                                        @click="openDeleteAvatarDialog(profileImage)"></v-icon>
                                    </v-btn>
                                    <v-btn icon size="x-small" density="confortable" variant="plain">
                                      <v-icon icon="fa-solid fa-plus fa-2xs" color="white"
                                        @click="uploadProfileImage"></v-icon>
                                    </v-btn>
                                  </v-btn-group>
                                </div>

                              </v-expand-transition>
                            </v-img>
                          </v-avatar>
                          <v-list-item :title="customer.first_name + customer.last_name"></v-list-item>

                          <v-list-item :title="customer.email"></v-list-item>

                          <v-list-item>
                            <v-btn class="me-2" color="primary" variant="text" @click="openDialogCustomerInfo(profileImage)" size="x-small">
                              Personal info
                            </v-btn>
                          </v-list-item>

                        </v-card>
                      </template>
                    </v-hover>
                  </v-sheet>
                </v-col>

                <v-col cols="auto" md="8">
                  <v-expansion-panels v-model="panel">
                      <v-expansion-panel>
                        <v-expansion-panel-title>
                          First Address
                        </v-expansion-panel-title>
                       
                       
                        <v-expansion-panel-text>
                          <v-sheet class="ma-2 pa-6">
                            <v-card elevation="0">

                              <v-card-text>
                                <v-row>
                                  <v-col cols="auto" md="12">
                                    <label>Address:</label>
                                    <v-text-field v-model="editedItem.address" required hide-details
                                      :label="customerAddress.endereco">
                                    </v-text-field>

                                  </v-col>

                                 
                                  <v-col cols="auto" sm="5">
                                    <label>Complement:</label>
                                    <v-text-field v-model="editedItem.complemento" required hide-details
                                      :label="customerAddress.complemento"
                                      :placeholder="customerAddress.complemento"></v-text-field>
                                  </v-col>
                                  
                                  <v-col cols="auto" md="3">
                                    <label>N°</label>
                                    <v-text-field v-model="editedItem.number" required hide-details :label="customerAddress.number"
                                      :placeholder="customerAddress.number"></v-text-field>
                                  </v-col>

                                  <v-col cols="auto" sm="4">
                                    <label>Bairro:</label>
                                    <v-text-field v-model="editedItem.bairro" required hide-details :label="customerAddress.bairro"
                                      :placeholder="customerAddress.bairro"></v-text-field>
                                  </v-col>
                                </v-row>
                                <v-row>

                                  <v-col cols="auto" md="3">
                                    <label>UF:</label>
                                    <v-select v-model="editedItem.uf" :label="customerAddress.uf" :items="ufs" item-title="uf"
                                      placeholder="UF" return-object single-line :placeholder="customerAddress.uf">

                                    </v-select>

                                  </v-col>
                                  <v-col cols="auto" sm="4">
                                    <label>State:</label>
                                    <v-text-field v-model="editedItem.estado" required hide-details :placeholder="uf.state"
                                      :label="customerAddress.estado"></v-text-field>
                                  </v-col>
                                </v-row>
                                <v-row>
                                  <v-col cols="auto" sm="4">
                                    <label>Postal code:</label>
                                    <v-text-field v-model="editedItem.zip_code" v-maska:[options] :label="customerAddress.cep"
                                      :placeholder="customerAddress.cep"></v-text-field>


                                  </v-col>
                                  <v-col cols="auto" sm="4">
                                    <label>Cidade:</label>
                                    <v-text-field v-model="editedItem.cidade" required hide-details :label="customerAddress.cidade"
                                      :placeholder="customerAddress.cidade"></v-text-field>
                                  </v-col>

                                </v-row>

                              </v-card-text>
                              <v-row>
                                <v-col cols="auto" sm="4">
                                  <label>Pais:</label>
                                  <v-select v-model="editedItem.country" aria-required hide-details :label="customerAddress.pais"
                                    :items="countryItems" :placeholder="customerAddress.pais">

                                  </v-select>
                                </v-col>
                                <v-col cols="auto" sm="5">
                                  <label>Phone:</label>
                                  <v-text-field v-model="editedItem.phone" required hide-details :label="customerAddress.telefone"
                                    v-maska:[phoneOptions] :placeholder="customerAddress.telefone"></v-text-field>
                                </v-col>
                                <v-col col="8" sm="6">
                                  <v-btn-group>
                                    <v-btn :disabled="loadingUpdate" :loading="loadingUpdate" class="text-none mb-4"
                                    color="indigo-darken-3" size="large" variant="plain"
                                    @click="editAddress(customerAddress)" v-if="Object.keys(customerAddress).length >= 1">
                                    Update
                                  </v-btn>
                                  <v-btn v-if="Object.keys(customerAddress).length >= 1" class="text-none mb-4" color="error" size="large" variant="plain" 
                                    @click="openDialogRemoveAddress(customerAddress)">
                                    Remove address
                                  </v-btn>
                                  <v-btn :disabled="loading" :loading="loading" block class="text-none mb-4"
                                    color="indigo-darken-3" size="x-large" variant="flat" @click="loading = !loading"
                                    v-else="object.keys(customerAddress).length == 0">
                                    Save and continue
                                  </v-btn>
                                
                                  </v-btn-group>
                                  
                                </v-col>
                              </v-row>


                            </v-card>
                            <div>
                              <ProfileUpload
                                v-model="dialogImage"
                                v-if="dialogImage"
                                @close-dialog="this.dialogImage = false"
                                @update-avatar="updateAvatar"
                              />
                              <ProfileCustomerDialog 
                                v-model="customerDialog" 
                                v-if="customerDialog"
                                :customer="this.customer"
                                @close-dialog="this.customerDialog = false"
                              />

                              <!-- Aqui vou carregar o dialog para remover o address-->
                              <RemoveAddressDialog 
                                v-model="dialogRemoveAddress"
                                v-if="dialogRemoveAddress"
                                :address="this.customerAddress"
                                @close-dialog="this.dialogRemoveAddress = false"
                              />
                            
                            </div>

                          </v-sheet>
                        </v-expansion-panel-text>
                      </v-expansion-panel>
                      <v-expansion-panel>
                        <v-expansion-panel-title>
                          Secondary Address
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                          Secondary form Address here...
                        </v-expansion-panel-text>
                      </v-expansion-panel> 
                      
                    </v-expansion-panels>
                </v-col>
              </v-row>

            </v-container>
          </div>

         
        </v-sheet>
      </v-col>
    </v-row>

    <div>
      <DeleteImageProfile
        v-model="deleteImageDialog"
        v-if="deleteImageDialog"
        :image="this.imageRemove"
        @close-dialog="closeDeleteAvatarDialog"
        @delete-image="deleteAvatar"
      />
    </div>
  </v-container>
</template>

<script setup>
import { ref } from "vue";
import { vMaska } from "maska";

const options = { mask: '#####-####' };
const myValue = ref('');

const phoneOptions = { mask: '55+ (##) #####-####' };
const phoneMask = ref('');
</script>

<script>

import axios from "axios";
import ProfileUpload from '../Dialogs/ProfileImage.vue'
import DeleteImageProfile from "../Profile/profileImage/deleteImageProfile.vue";
import ProfileCustomerDialog from '../Profile/partials/ProfileCustomerDialog.vue';
import RemoveAddressDialog from '../Profile/partials/removeAddress.vue';

export default {
  emits: ['close-dialog', 'delete-image'],
  components: {
    ProfileUpload,
    ProfileCustomerDialog,
    DeleteImageProfile,
    RemoveAddressDialog
 },
  data: () => ({
    customer: false,
    customerAddress: [],
    profileImage: [],
    panel: ['first_address', 'secondary_address'],
    address: '',
    number: 0,
    complemento: '',
    bairro: '',
    uf: '',
    cidade: '',
    estado: '',
    zip_code: '',
    country: '',
    phone: '',
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    confirmPassword: '',
    email: '',
    loading: false,
    loadingUpdate: false,
    dialogImage: false,
    customerDialog: false,
    deleteImageDialog: false,
    dialogRemoveAddress: false,
    imageId: -1,
    imageRemove: false,
    ufs: [{
      state: 'Distrito Federal', uf: 'DF',
      state: 'Goiás', uf: 'Go',
      state: 'Maranhão', uf: 'MA',
    }

      //     'RJ',
      //    'SP',
    ],
    countryItems: [
      'Brazil'
    ],
    editedItem: {
      address: '',
      number: '',
      complemento: '',
      bairro: '',
      uf: '',
      cidade: '',
      estado: '',
      zip_code: '',
      country: '',
      phone: '',
    },

  }),
  watch: {
    loading(val) {
      if (!val) return;
      this.save();
      setTimeout(() => (this.loading = false, 2000));
    },
    loadingUpdate(val) {
      if (!val) return;
      this.update();
      setTimeout(() => (this.loadingUpdate = false, 20000));
    },
    customerDialog(val) {
      val || this.closeCustomerDialog();
    },
    dialogImage(val) {
      val || this.closeImageDialog();
    }
  },
  methods: {
    getCustomer() {
      axios.get('/customer')
        .then((response) => {
          return this.customer = response.data;
        })
        .catch((response) => {
          return alert('Error :' + response);
        })
    },
    getProfileImage() {
      axios.get('/profileImage')
        .then((response) => {
          return this.profileImage = response.data;
        })
        .catch((response) => {
          return alert('Error: ' + response);
        })
    },
    openDeleteAvatarDialog(item){
      this.imageRemove = Object.assign({}, item);
      this.imageId = this.imageRemove.id;
      this.deleteImageDialog = true;
      
    },
    closeDeleteAvatarDialog(){
      this.deleteImageDialog = false;
    },
    deleteAvatar(image){
      return this.profileImage = '';
    },
    deleteProfileImage(item){
      //this.imageId = this.profileImage.indexOf(item);
     return this.removeProfileImage();
    },
    removeProfileImage() {
      const image = Object.assign({}, this.profileImage);
     
      axios.delete(`/profileImage/delete/${image.id}`)
        .then((response) => {
          console.log(this.profileImage);
          return this.profileImage.splice(this.imageId, 1);
        })
        .catch((response) => {
          return alert('Error :' + response);
        });
    },
    getAddress() {
      axios.get('/address')
        .then((response) => {
          return this.customerAddress = response.data;
        })
        .catch((response) => {
          return alert('Erro : ' + response);
        });
    },
    openDialogCustomerInfo(item) {
     
      this.editedItem = Object.assign({}, this.customerAddress);
     
      this.customerDialog = true;
      console.log(this.customerDialog);
    },
    updateAvatar(response){
      return this.profileImage = Object.assign({}, response.data.original);
    },
    closeImageDialog() {
      this.dialogImage = false;
    },
    openDialogRemoveAddress(item){
     // this.editedItem = Object.assign({}, item);
    //  this.editedIndex = this.customerAddress.indexOf(item);
      this.dialogRemoveAddress = true;
    },
    uploadProfileImage() {
      this.dialogImage = true;
    },
    save() {
      const data = {
        address: this.editedItem.address,
        number: this.editedItem.number,
        complemento: this.editedItem.complemento,
        bairro: this.editedItem.bairro,
        uf: this.editedItem.uf.uf,
        state: this.editedItem.uf.state,
        postal_code: this.editedItem.zip_code,
        city: this.editedItem.cidade,
        country: this.editedItem.country,
        phone: this.editedItem.phone,
      };
      axios.post('/address/save', data)
        .then((response) => {
          
          return this.customerAddress = response.data;
        })
        .catch((response) => {
          return alert('Error :' + response);
        });

    },
    editAddress() {
      return this.update();
    },
    update() {
      const data = {
        endereco: this.editedItem.address,
        number: this.editedItem.number,
        complemento: this.editedItem.complemento,
        bairro: this.editedItem.bairro,
        uf: this.editedItem.uf,
        estado: this.editedItem.state,
        cep: this.editedItem.zip_code,
        cidade: this.editedItem.cidade,
        pais: this.editedItem.country,
        telefone: this.editedItem.phone,
      }
    
      axios.post(`/address/update/${this.customerAddress.id}`, data)
        .then((response) => {
         
          return this.customerAddress = Object.assign({},response.data);
        })
        .catch((response) => {
          return alert('Error: ' + response);
        });
    }
  },
  created() {
    this.getCustomer();
    this.getProfileImage();
    this.getAddress();
  }
}
</script>

<style>
.v-card--reveal {
  align-items: center;
  top: 0;
  justify-content: center;
  opacity: .8;
  position: absolute;
  width: 100%;
  max-height: 30%;
}</style>