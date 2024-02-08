<template>
    <v-row>
        <v-col class="d-flex justify-center mb-6 flex-column">
            <v-container>
                <v-sheet>
                    <v-carousel 
                        v-for="(car_image, index) in carousel"
                        :key="index"
                        show-arrows="false"
                        hide-delimiters="false"
                        cycle
                        >
                        <v-carousel-item 
                            v-for="(image, index) in JSON.parse(car_image.images)" 
                            :key="index"
                            :src="`./storage/Carrousel/${image}`"
                            :lazy-src="`./storage/Carrousel/${image}`"
                            :alt="`${image}`"
                            cover
                        >
                     </v-carousel-item>
                       
                    </v-carousel>
                 
                </v-sheet>
            </v-container>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        props: ['carousel'],
    }
</script>