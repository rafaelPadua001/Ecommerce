<template>
  <div>
    <Dashboard />
  </div>

  <v-container>
    <v-row no-gutters>
      <v-col cols="12" sm="6">
        <v-sheet class="ma-2 pa-2">
          <div>
            <v-container>
              <v-row no-gutters>
                <v-col col="8" sm="4">
                  <v-sheet class="ma-2 pa-2">
                    <v-card class="mx-auto" max-width="434" rounded="0">
                      <v-avatar color="grey" size="130" rounded="0">
                        <v-img cover src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg">
                          <div class="float-end">
                            <v-btn icon size="x-small" density="confortable" variant="plain">
                              <v-icon icon="fa-solid fa-plus fa-2xs" color="primary" @click="uploadProfileImage"></v-icon>
                            </v-btn>
                          </div>
                         
                        </v-img>
                      </v-avatar>
                      <v-list-item :title="customer.first_name" :subtitle="customer.last_name"></v-list-item>

                      <v-list-item :title="customer.email"></v-list-item>

                      <v-list-item title="Personal info"  @click="editCustomerInfo"></v-list-item>

                    </v-card>
                  </v-sheet>
                </v-col>

                <v-col col="12" sm="8">
                  <v-sheet class="ma-2 pa-2">
                    <v-card :width="650">

                      <v-card-text>
                        <v-row>
                          <v-col col="6" sm="6">
                            <label>Address:</label>
                            <v-text-field v-model="address" required hide-details :label="customerAddress.endereco">

                            </v-text-field>

                          </v-col>

                          <v-col col="4" sm="4">
                            <label>N°</label>
                            <v-text-field v-model="number" required hide-details :label="customerAddress.number"
                              :placeholder="customerAddress.number"></v-text-field>
                          </v-col>
                          <v-col col="6" sm="6">
                            <label>Complement:</label>
                            <v-text-field v-model="complemento" required hide-details :label="customerAddress.complemento"
                              :placeholder="customerAddress.complemento"></v-text-field>
                          </v-col>

                          <v-col col="8" sm="6">
                            <label>Bairro:</label>
                            <v-text-field v-model="bairro" required hide-details :label="customerAddress.bairro"
                              :placeholder="customerAddress.bairro"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>

                          <v-col col="4" md="4">
                            <label>UF:</label>
                            <v-select v-model="uf" :label="customerAddress.uf" :items="ufs" item-title="uf"
                              placeholder="UF" return-object single-line :placeholder="customerAddress.uf">

                            </v-select>

                          </v-col>
                          <v-col col="8" sm="4">
                            <label>State:</label>
                            <v-text-field v-model="estado" required hide-details :placeholder="uf.state"
                              :label="customerAddress.estado"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col col="6" sm="6">
                            <label>Postal code:</label>
                            <v-text-field v-model="zip_code" v-maska:[options] :label="customerAddress.cep"
                              :placeholder="customerAddress.cep"></v-text-field>


                          </v-col>
                          <v-col col="8" sm="6">
                            <label>Cidade:</label>
                            <v-text-field v-model="cidade" required hide-details :label="customerAddress.cidade"
                              :placeholder="customerAddress.cidade"></v-text-field>
                          </v-col>

                        </v-row>

                      </v-card-text>
                      <v-row>
                        <v-col col="6" sm="4">
                          <label>Pais:</label>
                          <v-select v-model="country" aria-required hide-details :label="customerAddress.pais"
                            :items="countryItems" :placeholder="customerAddress.pais">

                          </v-select>
                        </v-col>
                        <v-col col="8" sm="6">
                          <label>Phone:</label>
                          <v-text-field v-model="phone" required hide-details :label="customerAddress.telefone"
                            v-maska:[phoneOptions] :placeholder="customerAddress.telefone"></v-text-field>
                        </v-col>
                        <v-col col="8" sm="6">
                          <v-btn :disabled="loadingUpdate" :loading="loadingUpdate" block class="text-none mb-4"
                            color="indigo-darken-3" size="x-large" variant="flat" @click="editAddress(customerAddress)"
                            v-if="customerAddress">
                            Update and continue
                          </v-btn>
                          <v-btn :disabled="loading" :loading="loading" block class="text-none mb-4"
                            color="indigo-darken-3" size="x-large" variant="flat" @click="loading = !loading"
                            v-if="!customerAddress">
                            Verify and continue
                          </v-btn>
                        </v-col>
                      </v-row>


                    </v-card>
                    <div>
                      <v-dialog v-model="dialogImage" v-if="dialogImage" width="1024">
                      <v-card class="text-center">
                        <v-card-title>
                          <v-toolbar color="transparent">
                            Profile image
                            <v-btn icon>
                              <v-icon icon="fas fa-close"></v-icon>
                            </v-btn>
                          </v-toolbar>
                        </v-card-title>

                        <v-card-text>
                          <v-row>
                            <v-col col="12" sm="12">
                             
                                <Upload />
                             
                             
                             
                            </v-col>
                           
                          </v-row>
                         
                        </v-card-text>

                        <v-card-actions>
                          <v-btn-group>
                            <v-btn>
                              Save
                            </v-btn>
                            <v-btn
                              @click="dialogImage = false"
                            >
                              Cancel
                            </v-btn>
                          </v-btn-group>
                        </v-card-actions>
                      </v-card>
                     
                    </v-dialog>

                    <v-dialog v-model="customerDialog" v-if="customerDialog" width="1024">
                      <v-card class="text-center">
                        <v-card-title>
                          <v-toolbar color="transparent">
                            Personal Information
                            <v-btn icon>
                              <v-icon icon="fas fa-close"></v-icon>
                            </v-btn>
                          </v-toolbar>
                        </v-card-title>

                        <v-card-text>
                          <v-row>
                            <v-col>
                              <label>Name:</label>
                              <v-text-field :label="customer.first_name"></v-text-field>
                            </v-col>
                            <v-col>
                              <label>Last name:</label>
                              <v-text-field :label="customer.last_name"></v-text-field>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col>
                              <label>Email:</label>
                              <v-text-field :label="customer.email"></v-text-field>
                            </v-col>
                            <v-col>
                              <label>New password:</label>
                              <v-text-field type="password" label="New Password"></v-text-field>
                            </v-col>
                            <v-col>
                              <label>Confirm password:</label>
                              <v-text-field label="Confirm new password"></v-text-field>
                            </v-col>
                          </v-row>
                         
                        </v-card-text>

                        <v-card-actions>
                          <v-btn-group>
                            <v-btn>
                              Save
                            </v-btn>
                            <v-btn
                              @click="customerDialog = false"
                            >
                              Cancel
                            </v-btn>
                          </v-btn-group>
                        </v-card-actions>
                      </v-card>
                     
                    </v-dialog>
                    </div>
                   
                  </v-sheet>

                </v-col>
              </v-row>

            </v-container>
          </div>

           <div>
            <v-row>
              <v-col col="12" sm="8">
                <v-card>
                  <v-card-text>
                    Endereço secundário
                  </v-card-text>
                </v-card>

              </v-col>
            </v-row>

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
import Dashboard from '../Auth/Dashboard.vue';
import axios from "axios";
import Upload from '../Profile/profileImage/upload.vue'

export default {
  components: {
    Dashboard,
    Upload

  },
  data: () => ({
    customer: false,
    customerAddress: [],
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
    customerDialog(val){
      val || this.closeCustomerDialog();
    },
    dialogImage(val){
      val || this.closeImageDialog();
    }
  },
  methods: {
    getCustomer() {
      axios.get('/customer')
        .then((response) => {
          console.log(response.data);
          return this.customer = response.data;
        })
        .catch((response) => {
          return alert('Error :' + response);
        })
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
    editCustomerInfo(){
      this.editedItem = Object.assign({}, this.customerAddress);
      this.customerDialog = true;
      
    },
    closeCustomerDialog(){
      this.customerDialog = false;
      
    },
    closeImageDialog(){
      this.dialogImage = false;
    },  
    uploadProfileImage(){
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
          return this.customerAddress.push(response.data);
        })
        .catch((response) => {
          return alert('Error :' + response);
        })

    },
    editAddress() {
      this.editedItem = Object.assign({}, this.customerAddress);
      return this.update();
    },
    update() {
      console.log(this.editedItem);
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
          return this.customerAddress.push(response.data);
        })
        .catch((response) => {
          return alert('Error: ' + response);
        });
    }
  },
  created() {
    this.getCustomer();
    this.getAddress();
  }
}
</script>