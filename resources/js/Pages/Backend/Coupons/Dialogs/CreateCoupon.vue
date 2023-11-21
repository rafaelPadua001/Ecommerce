<template>
  <v-card v-if="this.createDialog">
    <v-toolbar title="Coupons Generator">
      <v-btn icon @click="createDialog = false">
        <v-icon icon="fas fa-close fa-2xs"></v-icon>
      </v-btn>
    </v-toolbar>
    <v-card-text>
      <v-row fluid>
        <v-col>
          <v-text-field v-model="code" label="Código do Cupom" required :rules="codeRules"></v-text-field>

        </v-col>
      </v-row>

      <v-row fluid>
        <v-col>
          <v-text-field v-model="discountPercentage" label="Desconto (%)" required :rules="valueRules"></v-text-field>

        </v-col>
      </v-row>
    </v-card-text>

    <v-card-actions>
      <v-btn-group>
        <v-btn @click="createCupon">Save</v-btn>
        <v-btn @click="createDialog = false">Close</v-btn>
      </v-btn-group>
     
    </v-card-actions>
  </v-card>

 {{ cupons }}
</template>

<script>
import axios from 'axios';

export default {
  props: ['createDialog'],
  data: () => ({
    coupons: [],
    code: '',
    discountPercentage: '',
    codeRules: [
      v => !!v || 'Código do cupom é obrigatório',
      v => (v && v.length <= 10) || 'Máximo de 10 caracteres para o código',
    ],
    valueRules: [
      v => !!v || 'Valor do Cupom é obrigatório',
      v => parseFloat(v) >= 0 || 'Valor deve ser maior ou igual a zero'
    ],
  }),
  watch: {
    createDialog(val) {
      val || this.createDialog;
    },
  },
  methods: {
    createCupon() {
      const token = document.head.querySelector('meta[name="csrf-token"]').content;
     
      const data = {
        code_coupon: this.code,
        value_coupon: this.discountPercentage,
      };

      axios.post('/coupons/add', data, {
        headers: {
          'X-CSRF-TOKEN': token
        }
      })
      .then((response) => {
        alert('Cupon generated success');
        return this.coupons.push(response.data);
      })
      .catch((response) => {
        alert('Error: ' , response);
      });     
    },
   
  },
  mounted() {

  }
}
</script>

    