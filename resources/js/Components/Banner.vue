<template>
  <v-container>
    <v-row fluid>
      <v-col class="d-flex justify-center flex-column" cols="auto">
        <v-sheet  v-if="bannerImage">
          <v-card class="mx-auto elevation-0">
          <v-img
            :width="1800"
            :height="720"
            :src="'./storage/Banners/'+this.bannerImage 
                  ?? 
                  'https://img.freepik.com/free-vector/black-friday-sale-banner-with-gift-boxes_1017-34764.jpg?w=900&t=st=1702064958~exp=1702065558~hmac=63cc9b765b4bd3bac1a9ac41201e5ffb2780ed7cd79b3b733b78c906c00bfc7f'"
          >
          </v-img>
         </v-card>
        </v-sheet>

        <v-sheet v-else class="py-2 px-2">
          <v-card class="mx-auto elevation-0">
            <v-skeleton-loader 
              type="image"
              :width="1800"
              
            >
          
          </v-skeleton-loader>
            
         </v-card>
        
        </v-sheet>
     </v-col>
    </v-row>

  </v-container>
 
</template>

<script>
export default {
  data: () => ({
    bannerImage: false,
  }),
  methods: {
    getBanner(){
      axios.get('/api/banner')
      .then((response) => {
        return this.bannerImage = JSON.parse(response.data.image)
      })
      .catch((response) => {
        return false;
      })
    },
  },
  mounted(){
    this.getBanner();
  }
}
</script>