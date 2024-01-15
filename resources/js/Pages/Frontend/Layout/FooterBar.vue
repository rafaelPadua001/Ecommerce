<template>
    
           
                <v-footer color="transparent-darken-1">
                    <v-row justify="center" no-gutters>

                        <v-btn 
                            v-for="link in links"
                            :key="link"
                            color="black"
                            variant="text"
                            class="mx-2"
                            rounded="xl"
                        >
                            {{ link }}
                        </v-btn>
                        <v-col class="text-center mt-4" cols="12">
                            {{ new Date().getFullYear() }} - EcomerceClone
                        </v-col>
             

                    </v-row>
                   
                       
                        
                </v-footer>
          
    
</template>

<script>
export default {
    data: () => ({
        links: [
            'Home',
            'About us',
            'Team',
            'Services',
            'Blog',
            'Contact Us',
           
        ],
    }),
}

</script>