<template>
  <v-dialog 
      fullscreen
      :scrim="false"
      transition="dialog-bottom-transition"
  >
    <v-card class="mx-auto" v-if="this.createDialog">
      <v-toolbar title="Coupons Generator">
        <v-btn icon @click="close">
          <v-icon icon="fas fa-close fa-2xs"></v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-row fluid>
          <v-col cols="12" sm="2">
            <v-text-field v-model="code" label="Código do Cupom" required :rules="codeRules"></v-text-field>

          </v-col>
          <v-col cols="12" sm="2">
            <v-text-field v-model="discountPercentage" label="Desconto (%)" required :rules="valueRules"></v-text-field>

          </v-col>
        </v-row>
        <v-row fluid>
          <v-col cols="12" sm="4">
            <label>Init Date:</label>
            <v-date-picker
              v-model="init_date"
              show-adjacent-months
             
            ></v-date-picker>
            {{ init_date }}
          </v-col>
          <v-col cols="12" sm="4">
            <v-label>End Date</v-label>
            <v-date-picker
              v-model="end_date"
              show-adjacent-months
            >

            </v-date-picker>
            {{ end_date }}
          </v-col>
        </v-row>
        <v-row fluid>
          <v-col cols="2" sm="4">
            <v-label>Init Hour</v-label>
            <v-text-field
              v-model="init_hour"
              label="Init Hour"
             
              type="time"
              suffix="PST"
            ></v-text-field>
            {{ init_hour }}
          </v-col>
          <v-col cols="2" sm="4">
            <v-label>End Hour</v-label>
            <v-text-field
              v-model="end_hour"
              label="End Hour"
              type="time"
              suffix="PST"
            ></v-text-field>
            {{ end_hour }}
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" sm="6">
            <v-switch v-model="display" inset hide-details :label="`Display on main page: ${display.toString()}`" color="success"></v-switch>
          </v-col>
        </v-row>
      </v-card-text>

      <v-card-actions>
        <v-btn-group>
          <v-btn @click="createCupon">Save</v-btn>
          <v-btn @click="close">Close</v-btn>
        </v-btn-group>

      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios';

export default {
  props: ['createDialog', 'coupons'],
  data: () => ({
    //coupons: [],
    code: '',
    discountPercentage: '',
    init_date: '',
    end_date: '',
    init_hour: '',
    end_hour: '',
    display: false, 
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
    close() {
      this.$emit('close-dialog');
    },
    createCupon() {
      const token = document.head.querySelector('meta[name="csrf-token"]').content;

      const data = {
        code_coupon: this.code,
        value_coupon: this.discountPercentage,
        init_date: this.init_date ? this.init_date.toISOString().split('T')[0] : null,
        end_date: this.end_date ? this.end_date.toISOString().split('T')[0] : null,
        init_hour: this.init_hour,
        end_hour: this.end_hour,
        display: this.display
      };

      axios.post('/coupons/add', data, {
        headers: {
          'X-CSRF-TOKEN': token
        }
      })
        .then((response) => {
          //this.$emit('close-dialog');
          return this.$emit('create', response);

        })
        .catch((response) => {
          alert('Error: ', response);
        });
    },

  },
  mounted() {

  }
}
</script>

    