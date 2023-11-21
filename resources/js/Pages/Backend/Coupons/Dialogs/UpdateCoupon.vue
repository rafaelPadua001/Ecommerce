<template>
    <v-row fluid>
        <v-col cols="12" md="12">
            <v-card class="mx-auto" width="500">
                <template v-slot:append>
                       
                    <v-btn variant="plain" icon @click="close">
                        <v-icon icon="fas fa-close"></v-icon>
                    </v-btn>
                </template>
                <v-row fluid>
                    <v-col>
                        <v-text-field v-model="editCoupon.code" label="Código do Cupom" required
                            :rules="codeRules"></v-text-field>
                    </v-col>
                </v-row>

                <v-row fluid>
                    <v-col>
                        <v-text-field v-model="editCoupon.discount_percentage" label="Desconto (%)" required
                            :rules="valueRules"></v-text-field>

                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-card-actions>
                            <v-btn-group>
                                <v-btn varian="plain"  class="mb-2" @click="update(editCoupon)">Update</v-btn>
                                <v-btn variant="plain" class="mb-2" @click="close">Close</v-btn>
                            </v-btn-group>
                        </v-card-actions>
                    </v-col>
                </v-row>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';

export default {
    props: ['editCoupon'],
    data: () => ({
        codeRules: [
      v => !!v || 'Código do cupom é obrigatório',
      v => (v && v.length <= 10) || 'Máximo de 10 caracteres para o código',
    ],
    valueRules: [
      v => !!v || 'Valor do Cupom é obrigatório',
      v => parseFloat(v) >= 0 || 'Valor deve ser maior ou igual a zero'
    ],
    }),
    methods: {
        close(){
            this.$emit('close-dialog');
        },
        update(editCoupon){
            const token = document.head.querySelector('meta[name="csrf-token"]').content;
           
            const data = {
                code: editCoupon.code,
                discount_percentage: editCoupon.discount_percentage
            };
          
            axios.post(`/coupons/update/${editCoupon.id}`, data,
                {
                    headers: {
                    'X-CSRF-TOKEN': token
                    }
                }
            )
            .then((response) => {
                if(response){
                    this.$emit('close-dialog');
                   return this.$emit('update-coupon', response); 
                }
               
                //return Object.assing(this.editCoupon[coupon.id], coupon);
            })
            .catch((response) => {
                return alert('Erro: ' + response);
            });
        }
    }
}
</script>