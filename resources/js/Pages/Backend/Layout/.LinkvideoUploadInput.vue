<template>
    <v-text-field
      v-model="video_link"
      label="Videos"
      hint="www.youtube.com/watch?v="
      persistent-hint
      
    >
    <template v-slot:append>
         <v-btn @click="preview" density="compact" icon size="x-small">
            <v-icon icon="fas fa-upload fa-2xs">

            </v-icon> 
        </v-btn>
    </template>
    </v-text-field>
    <v-card>
        <iframe 
            id="ytplayer" 
            type="text/html"
            :src="`https://www.youtube.com/embed/${previewLink}`"
            frameborder="0" allowfullscreen
            autoplay="1"
            controls="0"
            fs="0"
            color="white"
            v-if="video_link"
            cover
        ></iframe>
    </v-card>
    
  </template>
  
<script>
    export default{
        data: () => ({
            video_link: '',
            previewLink: '',
        }),
        methods: {
        preview() {
            console.log(this.video_link.slice(32));
          return  this.previewLink = this.video_link.slice(32);
            
        },
        }
    }
</script>