<template>
   
    <v-dialog 
        fullscreen
        :scrim="false"
        transition="dialog-bottom-transition"
    >
        <v-card class="mx-auto">
            <template v-slot:append>

                <v-btn variant="plain" icon @click="close">
                    <v-icon icon="fas fa-close"></v-icon>
                </v-btn>
            </template>
            <v-row fluid>
                <v-col cols="12" sm="2">
                    <v-text-field v-model="editCoupon.code" label="Código do Cupom" required
                        :rules="codeRules"></v-text-field>
                </v-col>
                <v-col cols="12" sm="2">
                    <v-text-field v-model="editCoupon.discount_percentage" label="Desconto (%)" required
                        :rules="valueRules"></v-text-field>

                </v-col>
            </v-row>

           
            <v-row fluid>
                <v-col cols="12" sm="4">
                    <label>Init Date:</label>
                    <v-date-picker v-model="editCoupon.init_date" show-adjacent-months></v-date-picker>
                    {{ editCoupon.init_date }}
                </v-col>
                <v-col cols="12" sm="4">
                    <v-label>End Date</v-label>
                    <v-date-picker v-model="editCoupon.end_date" show-adjacent-months>

                    </v-date-picker>
                    {{ editCoupon.end_date }}
                </v-col>
            </v-row>
            <v-row fluid>
                <v-col cols="2" sm="4">
                    <v-label>Init Hour</v-label>
                    <v-text-field v-model="editCoupon.init_hour" label="Init Hour" type="time" suffix="PST"></v-text-field>
                    {{ editCoupon.init_hour }}
                </v-col>
                <v-col cols="2" sm="4">
                    <v-label>End Hour</v-label>
                    <v-text-field v-model="editCoupon.end_hour" label="End Hour" type="time" suffix="PST"></v-text-field>
                    {{ editCoupon.end_hour }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6">
                    <v-switch v-model="isDisplayed" inset hide-details :label="`Display on main page: ${editCoupon.is_displayed.toString()}`"
                        color="success"></v-switch>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-card-actions>
                        <v-btn-group>
                            <v-btn varian="plain" class="mb-2" @click="update(editCoupon)">Update</v-btn>
                            <v-btn variant="plain" class="mb-2" @click="close">Close</v-btn>
                        </v-btn-group>
                    </v-card-actions>
                </v-col>
            </v-row>
        </v-card>
    </v-dialog>
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
    computed: {
        isDisplayed:{
            get(){
                return this.editCoupon.is_displayed === 1;
            },
            set(value){
                this.editCoupon.is_displayed = value ? 1 : 0;
            }
        }
    },
    methods: {
        close() {
            this.$emit('close-dialog');
        },
        update(editCoupon) {
            const token = document.head.querySelector('meta[name="csrf-token"]').content;

            const data = {
                code: editCoupon.code,
                discount_percentage: editCoupon.discount_percentage,
                init_date: editCoupon.init_date ? editCoupon.init_date : null,
                end_date: this.editCoupon.end_date ? this.editCoupon.end_date : null,
                init_hour: this.editCoupon.init_hour,
                end_hour: this.editCoupon.end_hour,
                is_displayed: this.editCoupon.is_displayed
            };

            axios.post(`/coupons/update/${editCoupon.id}`, data,
                {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }
            )
                .then((response) => {
                    if (response) {
                        this.$emit('close-dialog');
                        return this.$emit('update-coupon', response);
                    }

                    //return Object.assing(this.editCoupon[coupon.id], coupon);
                })
                .catch((response) => {
                    return alert('Erro: ' + response);
                });
        },
        convertDisplyed(){
            this.$nextTick(() => {
                this.$refs.displaySwitch.label = `Display on main page: ${this.isDisplayed.toString()}`
            })
        }
        
    }
}
</script>