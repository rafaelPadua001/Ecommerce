<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="auto" >
        <v-sheet class="ma-2 pa-2">
          <div>
            <v-container>
              <v-row justify="center">
                <v-col cols="auto" sm="12">
                  <v-sheet class="ma-2 pa-2">
                    <v-hover>
                      <template v-slot="{ isHovering, props }">
                        <v-card class="mx-auto" width="150" v-bind="props" elevation="0">
                          <v-avatar color="grey" size="150">
                            <v-img color="surface-variant" cover
                              src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0s9szfX_siS_iDFcfPRqjxs0R1n9Qh2twgox6DHU-h1bBG9BtkBBVmmEWOGj35H3CIIU"
                              :alt="customer.name" v-if="Object.keys(profileImage).length == 0">

                              <v-expand-transition v-if="isHovering">
                                <template
                                  class="d-flex transition-fast-in-fast-out bg-grey-darken-4 v-card--reveal text-h5"
                                  v-if="Object.keys(profileImage).length == 0"
                                >
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
                          <v-list-item :title="customer.first_name + ' ' + customer.last_name"></v-list-item>

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

                <v-col cols="auto">
                  <v-expansion-panels v-model="panel">
                      <v-expansion-panel>
                        <v-expansion-panel-title bg-color="transparent">
                          First Address
                        </v-expansion-panel-title>
                       
                        <v-expansion-panel-text>
                          <FirstAddress 
                              :editedItem="this.editedItem"
                              :customerAddress="this.customerAddress"
                              :uf="this.uf"
                              @saveAddress="save"
                              @updateAddress="update"
                              @removeAddress="remove"/>
                            
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
                                @update-customer="update"
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
import { EventBus } from '@/Event/EventBus';
import FirstAddress from './partials/FirstAddress.vue';
import ProfileUpload from '../Dialogs/ProfileImage.vue'
import DeleteImageProfile from "../Profile/profileImage/deleteImageProfile.vue";
import ProfileCustomerDialog from '../Profile/partials/ProfileCustomerDialog.vue';
import RemoveAddressDialog from '../Profile/partials/removeAddress.vue';

export default {
  emits: ['close-dialog', 'delete-image'],
  components: {
    FirstAddress,
    ProfileUpload,
    ProfileCustomerDialog,
    DeleteImageProfile,
    RemoveAddressDialog
 },
  data: () => ({
    customer: false,
    customerAddress: [],
    profileImage: [],
    panel: [1, 0],
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
      EventBus.emit('delete-avatar-image', this.imageRemove);
      return this.profileImage = '';
    },
    deleteProfileImage(item){
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
      
    },
    updateAvatar(response){
      this.profileImage = Object.assign({}, response.data.original);
      return EventBus.emit('update-avatar-image', this.profileImage);
    //  return this.profileImage = Object.assign({}, response.data.original);
    },
    closeImageDialog() {
      this.dialogImage = false;
    },
    uploadProfileImage() {
      this.dialogImage = true;
    },
    save(response) {
        
        return this.customerAddress = response.data;
      },
    editAddress() {
      return this.update();
    },
    update(response) {
      return this.customerAddress = Object.assign({}, response.data);
    },
    remove(item){
     
      return this.customerAddress = '';
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