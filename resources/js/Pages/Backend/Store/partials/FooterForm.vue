<template>
    <v-container>
        <v-sheet class="py-2 px-2">
            <v-row justify="center" fluid>
            <v-row fluid>
                    <v-col class="d-flex justify-center flex-column" cols="auto">
                        <h4>Links:</h4>
                    </v-col>
                     <v-col class="d-flex justify-center flex-column" cols="auto">
                    <v-select 
                        label="Select links"
                        v-model="links"
                        :items="defaultLinks"
                        hint="Pick up your footer links"
                        multiple
                        persistent-hint
                    ></v-select>

                </v-col>

                
            </v-row>    
            <v-row v-if="links.length >= 1" fluid>
                    <v-col v-for="link in links" class="d-flex justify-center flex-column" cols="auto"> 
                        <v-btn type="text">
                            {{ link }}
                        </v-btn>
                    </v-col>
                </v-row>
                <!-- <v-col class="d-flex justify-center flex-column" cols="auto">
                    <v-select
                        label="Link position"
                        v-mode="linksPosition"
                        :items="defaultPositions"
                        hint="Select link position"
                        persistent-hint
                    >

                    </v-select>

                    {{ linksPosition }}
                </v-col> -->
                
            </v-row>

            <v-row  fluid>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <h4>Social Icons:</h4>
                </v-col>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <v-select
                        label="Social Midias"
                        v-model="socialMidiasIcon"
                        :items="defaultSocialMidias"
                        hint="Select Social midias icon"
                        multiple
                        persistent-hint
                    >

                    </v-select>
                </v-col>
                <v-row v-if="socialMidiasIcon.length >= 1" fluid>
                    <v-col v-for="(icon,index) in socialMidiasIcon" :key="index" class="d-flex justify-center flex-column" cols="auto">
                      <v-icon :icon="icon"></v-icon>  
                    </v-col>
            </v-row>
               
            </v-row>
            
            <v-row fluid>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <h4>Text:</h4>
                </v-col>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <v-textarea label="Footer text" v-model="footerText" clearable></v-textarea>
                </v-col>
            </v-row>

            <v-row fluid>
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <h4>Background color:</h4>
                </v-col>
                <v-col class="d-flex justify-space-around flex-column" cols="auto">
                    <v-color-picker
                    class="ma-2"
                    swatches-max-height="400px"
                    show-swatches
                    @click="previewColor()"
                    ></v-color-picker>
                 
                </v-col>

                <v-row v-if="swatches.length >= 1">
                    <v-col>
                        {{ swatches }}
                    </v-col>
                </v-row>
            </v-row>
        </v-sheet>
    </v-container>
</template>

<script>
    export default {
        data: () => ({
            links: [],
            defaultLinks: [
                {title: 'Home', props: {subtitle: '/'}, link: '/'},
                {title: 'About Us', props: {subtitle: '/aboutUs'}, link: '/aboutUs'},
                {title: 'Team', props: {subtitle: '/team'}, link: '/team'},
                {title: 'Services', props: {subtitle: '/services'}, link: '/services'},
                {title: 'Blog', props: {subtitle: '/blog'}, link: '/blog'},
                {title: 'Contact Us', props: {subtitle: '/contactUs'}, link: '/contactUs'},
            ],
            FooterText: '',
             socialMidiasIcon: [],
             defaultSocialMidias: [
                 {title: 'Facebook', props:{ subtitle: 'fa-brands facebook'}, value: 'fa-brands fa-facebook'},
                 {title : 'X (twitter)', props: {subtitle: 'fa-brands fa-x-twitter'}, value: 'fa-brands fa-x-twitter'},
                 {title: 'Instagram', props: {subtitle: 'fa fa-brands instagram'}, value: 'fa-brands fa-instagram'},
                {title:'WhatsApp', props: {subtitle: 'fa-brands fa-whatsapp'}, value: 'fa-brands fa-whatsapp'},
                {title: 'Discord', props: {subtitle: 'fa-brands fa-discord'}, value: 'fa-brands fa-discord'},
                {title: 'E-mail', props: {subtitle: 'fa-solid fa-envelope'}, value:'fa-solid fa-envelope'},
                {title: 'LinkedIn', props: {subtitle: 'fa-brands fa-linkedin'}, value:'fa-brands fa-linkedin'},
                {title: 'Telegram', props: {subtitle: 'fa-brands fa-telegram'}, value:'fa-brands fa-telegram'},
                {title: 'Twitch', props: {subtitle: 'fa-brands fa-twitch'}, value:'fa-brands fa-twitch'},
                {title: 'Youtube', props: {subtitle: 'fa-brands fa-youtube'}, value:'fa-brands fa-youtube'},

            ],
            swatches: [
        
            ],
            footerText: '',
        }),
        methods:{
            previewColor(){
                console.log(this.swatches);
            }
        }
    }
</script>