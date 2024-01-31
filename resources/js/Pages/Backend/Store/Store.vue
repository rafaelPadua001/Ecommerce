<template>
    <div>
        <Dashboard />
    </div>

    <v-container>
        <v-row fluid justify="center">
            <v-col class="d-flex justify-center flex-column" cols="12">
                <v-sheet>
                    <v-tabs v-model="tab" color="deep-purple-accent-4" align-tabs="center">
                        <v-tab value="tab-1">App</v-tab>
                        <v-tab value="tab-2">Style</v-tab>
                        <v-tab value="tab-3">Landscape</v-tab>
                    </v-tabs>
                    <v-window v-model="tab">
                        <v-window-item :value="'tab-1'">
                            <v-container fluid>
                                <v-row>
                                    <v-col>
                                        <v-card class="mx-auto" v-if="this.editIndex == -1">

                                            <v-card-text>
                                                <v-row fluid>
                                                    <v-col>
                                                        <v-text-field v-model="nameApp" label="App Name" hide-details="auto"
                                                            :rules="nameAppRules"></v-text-field>

                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field v-model="mailApp" label="Email App"
                                                            :rules="mailRules">

                                                        </v-text-field>


                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-file-input v-model="appIcon" label="Logo image" accept="image/*"
                                                            v-bind:change="handleFile()"></v-file-input>


                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field v-model="phoneApp" label="Phone App"
                                                            v-maska:[phoneOptions] :rules="phoneRules">

                                                        </v-text-field>

                                                    </v-col>
                                                </v-row>



                                                <v-row>
                                                    <v-col>
                                                        <v-text-field v-model="addressApp" label="Address App">

                                                        </v-text-field>


                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-btn-group>
                                                    <v-btn class="me-2" @click="save" variant="plain">Save</v-btn>
                                                    <v-btn class="me-2" @click="cancel" variant="plain">Cancel</v-btn>
                                                </v-btn-group>
                                            </v-card-actions>

                                        </v-card>

                                        <v-card v-else>
                                          
                                            <v-card-text>
                                                <v-row fluid>
                                                    <v-col>
                                                        <v-text-field v-model="editItem.app_name" label="App Name"
                                                            hide-details="auto" :rules="nameAppRules"></v-text-field>

                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field v-model="editItem.app_mail" label="Email App"
                                                            :rules="mailRules">

                                                        </v-text-field>


                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-file-input v-model="appIcon" label="Logo image" accept="image/*"
                                                            v-bind:change="handleFile()"></v-file-input>
                                                    </v-col>
                                                    <v-col>
                                                        <v-text-field v-model="editItem.app_phone" label="Phone App"
                                                            v-maska:[phoneOptions] :rules="phoneRules">
                                                        </v-text-field>

                                                    </v-col>
                                                </v-row>



                                                <v-row>
                                                    <v-col>
                                                        <v-text-field v-model="editItem.app_address" label="Address App">

                                                        </v-text-field>


                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-btn-group>
                                                    <v-btn class="me-2" @click="save" variant="plain">update</v-btn>
                                                    <v-btn class="me-2" @click="removeDialog(editItem)" variant="plain" color="red">remove</v-btn>
                                                    <v-btn class="me-2" @click="cancel" variant="plain">Cancel</v-btn>
                                                </v-btn-group>
                                            </v-card-actions>


                                        </v-card>

                                        <div>
                                            <DeleteDialog 
                                                v-model="deleteDialog"
                                                :store="this.editItem"
                                                @remove-store="removeStore"
                                                @close-dialog="closeDelete"
                                            />

                                            <v-snackbar v-model="snackbar">
                                                {{ message }}
                                            </v-snackbar>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-window-item>
                        <v-window-item value="tab-2">
                           <FormStyle :store="this.editItem" @update="updateStyle"/>
                        </v-window-item>
                    </v-window>
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
import FormStyle from './partials/formStyle.vue';
import DeleteDialog from './partials/delete.vue';
import axios from "axios";
export default {
    components: {
        Dashboard,
        FormStyle,
        DeleteDialog
    },
    data: () => ({
        store: [],
        editIndex: -1,
        editItem: {},
        tab: null,
        snackbar: false,
        message: '',
        nameApp: null,
        nameAppRules: [
            value => !!value || 'required.',
            value => (value && value.length >= 3) || 'min is 3 characters',
        ],
        appIcon: [],
        mailApp: null,
        mailRules: [
            value => {
                if (value) return true;
                return 'E-mail is required.';
            },
            value => {
                if ('/.+@+\..+'.test(value)) return true;
                return 'E-mail must be valid.';
            }
        ],
        phoneApp: null,
        phoneRules: [
            value => {
                if (value) return true;
                return 'Phone is required';
            },
            value => {
                if (value?.length > 9 && /[0-9]+/.test(value)) return true;
                return 'Phone number needs to at least 9 digits';
            }
        ],
        addressApp: null,
        deleteDialog: false,
    }),
    watch: {
        closeDelete(val){
            val || this.closeDelete();
        }
    },
    methods: {
        getStore() {
            axios.get('/store')
                .then((response) => {
                    if (Object.keys(response.data).length >= 1) {
                        this.editItem = Object.assign({}, response.data);
                        this.editIndex = 1;
                        return this.editItem;
                    }
                    console.log(this.editIndex);
                    return this.store = response.data;
                })
                .catch((response) => {
                    return false; 
                    alert('Error: ' + response);
                });
        },
        handleFile() {
            const image = this.appIcon;
            console.log(image);
        },
        removeDialog(item){
            this.deleteDialog = true;
        },
        closeDelete(){
            return this.deleteDialog = false;
        },
        removeStore(item){
            this.snackbar = true;
            this.message = 'Removed store';
            return this.snackbar = true;
        },
        save() {
            if (this.editIndex == 1) {
                const token = document.head.querySelector('meta[name="csrf-token"]').content;
                const data = {
                    app_name: this.editItem.app_name,
                    app_mail: this.editItem.app_mail,
                    app_logo: this.appIcon,
                    app_phone: this.editItem.app_phone,
                    app_address: this.editItem.app_address,
                }
                axios.post(`/store/update/${this.editItem.id}`, data, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then((response) => {
                        this.snackbar = true;
                        this.message = 'Updated store';
                        return this.store = response.data;;
                    })
                    .catch((response) => {
                        return alert('Error:' + response);
                    });
                  
                    return true;
                   
            }
            const token = document.head.querySelector('meta[name="csrf-token"]').content;
            const data = {
                app_name: this.nameApp,
                app_mail: this.mailApp,
                app_logo: this.appIcon,
                app_phone: this.phoneApp,
                app_address: this.addressApp,
            }
            axios.post(`/store/create`, data, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((response) => {
                    this.editItem = response.data
                    this.message = 'Saved Store';
                    this.snackbar = true;
                    return this.store = this.editItem;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
        }
    },
    mounted() {
        this.getStore();
    }
}
</script>