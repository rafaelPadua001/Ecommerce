<template>
  <v-dialog fullscreen :scrim="false" transition="dialog-bottom-transition" scrollable>

    <v-card class="mx-auto" v-if="this.createDialog">
      <v-toolbar title="Coupons Generator" color="transparent">
        <v-btn icon @click="close">
          <v-icon icon="fas fa-close fa-2xs"></v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-row fluid>
          <v-col cols="12" sm="6">
            <v-text-field v-model="code" label="Código do Cupom" required :rules="codeRules"></v-text-field>

          </v-col>
          <v-col cols="12" sm="6">
            <v-text-field v-model="discountPercentage" label="Desconto (%)" required :rules="valueRules"></v-text-field>

          </v-col>
          <v-col cols="12" sm="6">
        
              <v-file-input v-model="image" accept="image/*" label="File input"></v-file-input>
            
          </v-col>
        </v-row>

        <v-row fluid>
           <v-col cols="12" sm="6">
            <label>Init Date:</label>
            <v-date-picker v-model="init_date" show-adjacent-months></v-date-picker>
            {{ formatted_init_date }}
          </v-col>
          <v-col cols="12" sm="6">
            <v-label>End Date</v-label>
            <v-date-picker v-model="end_date" show-adjacent-months>

            </v-date-picker>
            {{ formatted_end_date }}
          </v-col>
        </v-row>
        <v-row fluid>
          <v-col cols="12" sm="2">
            <v-label>Init Hour</v-label>
            <v-text-field v-model="init_hour" label="Init Hour" type="time" suffix="PST"></v-text-field>
            {{ init_hour }}
          </v-col>
          <v-col cols="12" sm="2">
            <v-label>End Hour</v-label>
            <v-text-field v-model="end_hour" label="End Hour" type="time" suffix="PST"></v-text-field>
            {{ end_hour }}
          </v-col> 
        </v-row>
        <v-row>
          <v-col cols="12" sm="6">
            <v-switch v-model="display" inset hide-details :label="`Display on main page: ${display.toString()}`"
              color="success"></v-switch>
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
    image: [],
    init_date: new Date(),
    end_date: new Date(),
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
  computed: {
    formatted_init_date(){
      return this.init_date.toString().replace(/\d{2}:\d{2}:\d{2}.*/, '');
    },
    formatted_end_date(){
      return this.end_date.toString().replace(/\d{2}:\d{2}:\d{2}.*/, '');
    }
  },
  methods: {
    close() {
      this.$emit('close-dialog');
    },
    handleFiles() {
      this.$refs.image[0];
    },
    createCupon() {
      const token = document.head.querySelector('meta[name="csrf-token"]').content;

      const data = {
        code_coupon: this.code,
        value_coupon: this.discountPercentage,
        image: this.image,
        init_date: this.init_date ? this.init_date.toISOString().split('T')[0] : null,
        end_date: this.end_date ? this.end_date.toISOString().split('T')[0] : null,
        init_hour: this.init_hour,
        end_hour: this.end_hour,
        display: this.display
      };
      console.log(data);
      axios.post('/coupons/add', data, {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'multipart/form-data' 
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

    