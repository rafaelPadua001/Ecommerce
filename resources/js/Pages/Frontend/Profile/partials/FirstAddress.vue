2<template>
    <v-container>
        <v-sheet>
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
                            <v-text-field v-model="editedItem.number" required hide-details
                                :label="customerAddress.number" :placeholder="customerAddress.number"></v-text-field>
                        </v-col>

                        <v-col cols="auto" sm="4">
                            <label>Bairro:</label>
                            <v-text-field v-model="editedItem.bairro" required hide-details
                                :label="customerAddress.bairro" :placeholder="customerAddress.bairro"></v-text-field>
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
                            <v-text-field v-model="editedItem.estado" required hide-details
                                :placeholder="editedItem.uf.state" :label="customerAddress.estado"
                                :value="editedItem.uf.estado"></v-text-field>
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
                            <v-text-field v-model="editedItem.cidade" required hide-details
                                :label="customerAddress.cidade" :placeholder="customerAddress.cidade"></v-text-field>
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
                            <v-btn v-if="Object.keys(customerAddress).length >= 1" class="text-none mb-4" color="error"
                                size="large" variant="plain" @click="openDialogRemoveAddress(customerAddress)">
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

            <RemoveAddressDialog v-model="dialogRemoveAddress" v-if="dialogRemoveAddress"
                :address="this.customerAddress" @close-dialog="this.dialogRemoveAddress = false" @removeAddress="removeAddress"/>
        </v-sheet>
    </v-container>

</template>

<script>
import RemoveAddressDialog from './removeAddress.vue';

export default {
    name: 'FirstAddress',
    props: ['editedItem', 'customerAddress', 'uf'],
    components: {
        RemoveAddressDialog
    },
    data: () => ({
        address: '',
        customerAddress: [],
        number: 0,
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
        dialogRemoveAddress: false,
        ufs: [
            { state: 'Distrito Federal', uf: 'DF' },
            { state: 'Goiás', uf: 'Go' },
            { state: 'Maranhão', uf: 'MA' },

        ],
        countryItems: [
            'Brazil'
        ],
        
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
        dialogImage(val) {
            val || this.closeImageDialog();
        }
    },
    methods: {
        openDialogRemoveAddress(item) {
            this.customerAddress = item;
            this.dialogRemoveAddress = true;
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
                    return this.saveAddress(response);

                })
                .catch((response) => {
                    return alert('Error :' + response);
                });


        },
        saveAddress(response) {

            return this.$emit('saveAddress', response);
        },
        editAddress(item) {
            const data = {
                endereco: this.editedItem.address,
                number: this.editedItem.number,
                complemento: this.editedItem.complemento,
                bairro: this.editedItem.bairro,
                UF: this.editedItem.uf.uf,
                estado: this.editedItem.uf.state,
                cep: this.editedItem.zip_code,
                cidade: this.editedItem.cidade,
                pais: this.editedItem.country,
                telefone: this.editedItem.phone,
            };
            axios.post(`/address/update/${item.id}`, data)
                .then((response) => {
                    alert(response);
                    return this.updateAddress(response);

                })
                .catch((response) => {
                    return alert('Error :' + response);
                });


        },
        updateAddress(response) {
            return this.$emit('updateAddress', response);
        },
        removeAddress(item){
           
            return this.$emit('removeAddress', item);
        }
    }

}
</script>