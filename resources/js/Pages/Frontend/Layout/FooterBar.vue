<template>
    <v-container>
        <v-sheet class="px-2 py-2">
            <v-row v-for="(fo, index) in footer" :key="index" fluid>
                <v-col class="d-flex justify-center flex-column" cols="12">
                    <v-footer :color="fo.color">
                        <div>
                             <div class="align-left">
                                 <v-img :src="`./storage/app_icon/${store.app_logo}`"
                                        :lazy-src="`./storage/app_icon/${store.app_logo}`"
                                        :alt="store.app_log" :width="80">
                                    </v-img>
                            </div>
                            
                        </div>

                        <v-spacer></v-spacer>
                        <div>
                            <div class="align-left">
                                    <v-btn class="mx-4" v-for="(link, index) in JSON.parse(fo.links)" :key="index" 
                                    size="x-small" variant="plain" :to="'/' + link.toLowerCase()">
                                    {{ link }}
                                </v-btn>
                                <v-spacer></v-spacer>
                            </div>
                        </div>
                     
                     

                        <v-spacer></v-spacer>
                    
                    <v-divider></v-divider>
                      <div>
                        <div class="pt-0">
                            {{ fo.text }}

                          
                        </div>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <div class="px-4 py-2 text-center w-100">
                           
                           {{ new Date().getFullYear() }} - <strong>{{ store.app_name }}</strong>
                       </div>
                      </div>
                        <v-divider></v-divider>
                       <div>
                        <div class="d-flex flex-column" v-for="(icon, index) in JSON.parse(fo.icons)" :key="index">
                            <v-btn  :icon="icon" class="mx-4" size="x-small"
                                variant="plain"
                            >

                            </v-btn>
                            
                        </div>
                       </div>
                        
                    </v-footer>
                </v-col>
            </v-row>

        </v-sheet>
    </v-container>



</template>

<script>
import axios from 'axios';

export default {
    data: () => ({
        store: [],
        footer: [],
        links: [
            'Home',
            'About us',
            'Team',
            'Services',
            'Blog',
            'Contact Us',

        ],
        icons: [
            'fa-brands fa-facebook-f',
            'fa-brands fa-twitter',
            //'mdi-linkedin',
            'fa-brands fa-instagram',
        ],
    }),
    methods: {
        getStore() {
            axios.get('/store')
                .then((response) => {
                    this.store.app_logo = JSON.parse(response.data.app_logo);
                    this.store = response.data
                })
                .catch((response) => {
                    return false;
                })
        },
        getFooter() {
            axios.get('/footer')
                .then((response) => {
                    return this.footer = response.data;
                }).catch((response) => {
                    return alert('Error: ' + response);
                });
        }
    },
    created() {
        this.getStore();
        this.getFooter();
    }
}

</script>