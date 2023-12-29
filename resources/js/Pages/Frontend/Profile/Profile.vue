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
                                        @click="deleteProfileImage"></v-icon>
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
                            <v-btn class="me-2" color="primary" variant="text" @click="editCustomerInfo" size="x-small">
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
                                    <v-text-field v-model="address" required hide-details
                                      :label="customerAddress.endereco">
                                    </v-text-field>

                                  </v-col>

                                 
                                  <v-col cols="auto" sm="5">
                                    <label>Complement:</label>
                                    <v-text-field v-model="complemento" required hide-details
                                      :label="customerAddress.complemento"
                                      :placeholder="customerAddress.complemento"></v-text-field>
                                  </v-col>
                                  
                                  <v-col cols="auto" md="3">
                                    <label>N°</label>
                                    <v-text-field v-model="number" required hide-details :label="customerAddress.number"
                                      :placeholder="customerAddress.number"></v-text-field>
                                  </v-col>

                                  <v-col cols="auto" sm="4">
                                    <label>Bairro:</label>
                                    <v-text-field v-model="bairro" required hide-details :label="customerAddress.bairro"
                                      :placeholder="customerAddress.bairro"></v-text-field>
                                  </v-col>
                                </v-row>
                                <v-row>

                                  <v-col cols="auto" md="3">
                                    <label>UF:</label>
                                    <v-select v-model="uf" :label="customerAddress.uf" :items="ufs" item-title="uf"
                                      placeholder="UF" return-object single-line :placeholder="customerAddress.uf">

                                    </v-select>

                                  </v-col>
                                  <v-col cols="auto" sm="4">
                                    <label>State:</label>
                                    <v-text-field v-model="estado" required hide-details :placeholder="uf.state"
                                      :label="customerAddress.estado"></v-text-field>
                                  </v-col>
                                </v-row>
                                <v-row>
                                  <v-col cols="auto" sm="4">
                                    <label>Postal code:</label>
                                    <v-text-field v-model="zip_code" v-maska:[options] :label="customerAddress.cep"
                                      :placeholder="customerAddress.cep"></v-text-field>


                                  </v-col>
                                  <v-col cols="auto" sm="4">
                                    <label>Cidade:</label>
                                    <v-text-field v-model="cidade" required hide-details :label="customerAddress.cidade"
                                      :placeholder="customerAddress.cidade"></v-text-field>
                                  </v-col>

                                </v-row>

                              </v-card-text>
                              <v-row>
                                <v-col cols="auto" sm="4">
                                  <label>Pais:</label>
                                  <v-select v-model="country" aria-required hide-details :label="customerAddress.pais"
                                    :items="countryItems" :placeholder="customerAddress.pais">

                                  </v-select>
                                </v-col>
                                <v-col cols="auto" sm="5">
                                  <label>Phone:</label>
                                  <v-text-field v-model="phone" required hide-details :label="customerAddress.telefone"
                                    v-maska:[phoneOptions] :placeholder="customerAddress.telefone"></v-text-field>
                                </v-col>
                                <v-col col="8" sm="6">
                                  <v-btn :disabled="loadingUpdate" :loading="loadingUpdate" block class="text-none mb-4"
                                    color="indigo-darken-3" size="x-large" variant="flat"
                                    @click="editAddress(customerAddress)" v-if="Object.keys(customerAddress).length >= 1">
                                    Update
                                  </v-btn>
                                  <v-btn :disabled="loading" :loading="loading" block class="text-none mb-4"
                                    color="indigo-darken-3" size="x-large" variant="flat" @click="loading = !loading"
                                    v-else="object.keys(customerAddress).length == 0">
                                    Save and continue
                                  </v-btn>
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

                             <v-dialog v-model="customerDialog" v-if="customerDialog" width="1024">
                                <v-card class="text-center">
                                  <v-card-title>
                                    <v-toolbar color="transparent">
                                      Edit Information
                                      <template v-slot:append>
                                        <v-btn icon @click="closeCustomerDialog">
                                          <v-icon icon="fas fa-close"></v-icon>
                                        </v-btn>
                                      </template>

                                    </v-toolbar>
                                  </v-card-title>

                                  <v-card-text>
                                    <v-row>
                                      <v-col>
                                        <label>First name:</label>
                                        <v-text-field v-model="first_name" :label="customer.first_name"></v-text-field>
                                      </v-col>
                                      <v-col>
                                        <label>Last name:</label>
                                        <v-text-field v-model="last_name" :label="customer.last_name"></v-text-field>
                                      </v-col>
                                    </v-row>
                                    <v-row>
                                      <v-col>
                                        <label>Email:</label>
                                        <v-text-field v-model="email" :label="customer.email"></v-text-field>
                                      </v-col>
                                      <v-col>
                                        <label>New password:</label>
                                        <v-text-field v-model="password" type="password"
                                          label="New Password"></v-text-field>
                                      </v-col>
                                      <v-col>
                                        <label>Confirm password:</label>
                                        <v-text-field label="Confirm new password"></v-text-field>
                                      </v-col>
                                    </v-row>

                                  </v-card-text>

                                  <v-card-actions>
                                    <v-btn-group>
                                      <v-btn @click="customerUpdate">
                                        Save
                                      </v-btn>
                                      <v-btn @click="customerDialog = false">
                                        Cancel
                                      </v-btn>
                                    </v-btn-group>
                                  </v-card-actions>
                                </v-card>

                              </v-dialog>
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
//import Dashboard from '../Auth/Dashboard.vue';
import axios from "axios";

import ProfileUpload from '../Dialogs/ProfileImage.vue'

export default {
  components: {
    //  Dashboard,
//    Upload,
    ProfileUpload,

  },
  data: () => ({
    customer: false,
    customerAddress: [],
    profileImage: false,
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
    customerUpdate() {
      const selectCustomer = Object.assign({}, this.customer);
      let data = {
        first_name: this.first_name,
        last_name: this.last_name,
        email: this.email,
        password: this.password,

      };
      axios.post(`/customer/update/${selectCustomer.id}`, data)
        .then((response) => {
          return this.customer.push(response.data);
        })
        .catch((response) => {
          return alert('Error :' + response);
        });
    },
    deleteProfileImage() {
      const image = Object.assign({}, this.profileImage);

      axios.delete(`/profileImage/delete/${image.id}`)
        .then((response) => {
          this.profileImage.splice(image.id, 1);
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
    editCustomerInfo(item) {
      this.editedItem = Object.assign({}, this.customerAddress);
      this.customerDialog = true;

    },
    updateAvatar(response){
      return this.profileImage = Object.assign({}, response.data.original);
    },
    closeCustomerDialog() {
      this.customerDialog = false;

    },
    closeImageDialog() {
      this.dialogImage = false;
    },
    uploadProfileImage() {
      this.dialogImage = true;
    },
    save() {
      const data = {
        address: this.address,
        number: this.number,
        complemento: this.complemento,
        bairro: this.bairro,
        uf: this.uf.uf,
        state: this.uf.state,
        postal_code: this.zip_code,
        city: this.cidade,
        country: this.country,
        phone: this.phone,
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
      this.editedItem = Object.assign({}, this.customerAddress);
      return this.update();
    },
    update() {
      const data = {
        endereco: this.editedItem.endereco,
        // number: this.number,
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
  justify-content: start;
  opacity: .1;
  position: absolute;
  width: 100%;
  max-height: 30%;
}</style>