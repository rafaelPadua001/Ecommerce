<template>
    <div>
      <v-file-input v-model="images" @change="previewImages" multiple chips></v-file-input>
      <div v-if="previewImages" >
        <v-row v-if="images.length > 0">
          <v-col v-for="(image, index) in images" :key="index" cols="12" md="6">
            <v-card>
              
              <v-img 
                  v-model="images" 
                  :src="image.src" 
                  :lazy-src="image.name"
                  :alt="'Image ' + index"
                  cover
                >
                <div float="right" justify="center">
                  <v-btn icon="fas fa-close" size="12px" density="compact">
                    <v-icon icon="fas fa-close" size="10px"></v-icon>

                  </v-btn>
                </div>
               
                </v-img>
                
             </v-card>

             
          </v-col>
          <v-divider></v-divider>
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
        </v-row>
      </div>
     
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        images: [],
      };
    },
    watch: {
      previewImages(newUrl, oldUrl) {
        // Executar lógica quando a imagem é alterada
        console.log(`A imagem foi alterada de ${oldUrl} para ${newUrl}`);
      },
    },
    methods: {
      previewImages(event) {
      const files = event.target.files;
      if (files) {
        this.images = [];
        for (let i = 0; i < files.length; i++) {
          const reader = new FileReader();
          const file = files[i];

          reader.onload = (e) => {
            this.images.push({
              src: e.target.result,
              name: file.name,
            });
          };
          reader.readAsDataURL(file);
        }
      }
    },
    },
    
  };
  </script>
  
  <style scoped>
  /* Adicione estilos conforme necessário */
  </style>