<template>
  <v-app id="inspire">
    <v-main>
      <v-container>
      <div align='center'>
        <Banner></banner>
      
      </div>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
        
       <h4>All Products</h4>
        
        <v-spacer></v-spacer>
        
        <v-divider></v-divider>
        
        <v-row>
        <v-col
            v-for="n in 10"
            :key="n"
            cols="4"
          >
            
            <a href="#">
               <v-card
                class="mx-auto"
                max-width="400"
            >
                <v-img
                class="align-end text-white"
                height="300"
                lazy-src="https://a-static.mlcdn.com.br/450x450/smartphone-samsung-galaxy-a04e-64gb-azul-4g-octa-core-3gb-ram-65-cam-dupla-selfie-5mp/magazineluiza/236591500/f38524e949ce98131d76614599740b3d.jpg"
                src="https://a-static.mlcdn.com.br/450x450/smartphone-samsung-galaxy-a04e-64gb-azul-4g-octa-core-3gb-ram-65-cam-dupla-selfie-5mp/magazineluiza/236591500/f38524e949ce98131d76614599740b3d.jpg"
                cover
                >
                  <template>
                      <div class="d-flex align-center justify-center fill-height">
                          <v-progress-circular
                              color="grey-lighten-4"
                          >
                          </v-progress-circular>
                      </div>
                  </template>
            
               </v-img>
              <v-card-title>Top 10 Smartphones</v-card-title>
                  <v-card-subtitle class="pt-4">
                    Number 10
                  </v-card-subtitle>

                  <v-card-text>
                    <div>Celular Smartphone</div>

                    <div>Descrição do produto</div>
                  </v-card-text>

                  <v-card-actions>
                    <v-btn color="orange">
                      Carrinho       
                    </v-btn>

                    <v-btn color="orange">
                      Comprar
                    </v-btn>
                  </v-card-actions>
                </v-card>
            </a>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
    import Banner from '../../Components/Banner.vue';
   
    
    export default{
        components: {
            Banner,
            
        },
        
    }
</script>