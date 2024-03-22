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
                <v-col class="d-flex justify-center flex-column" cols="auto">
                    <v-color-picker
                    v-model="swatches"
                    class="ma-2"
                    swatches-max-height="100px"
                    show-swatches
                    @change="previewColor()"
                    ></v-color-picker>
                   
                </v-col>

                
                    <v-col>
                        <v-card class="mx-auto" :color="swatches" variant="elevated">
                          
                            <v-card-text>
                                <v-row fluid>
                                    <v-col v-for="(link,index) in links" :key="index">
                                        {{ link }}
                                    </v-col>
                                </v-row>
                               
                              
                            </v-card-text>
                            <v-card-text>
                                <v-row fluid>
                                    <v-col v-for="(icon,index) in socialMidiasIcon">
                                        <v-icon :icon="icon"></v-icon>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-text>
                                <v-row>
                                    <v-col fluid>
                                       <p>{{ footerText }}</p> 
                                    </v-col>
                                </v-row>
                            </v-card-text>
                          
                        </v-card>
                    </v-col>
                    <v-btn @click="submitForm">Submit</v-btn>
            </v-row>
        </v-sheet>
    </v-container>
</template>

<script>
    export default {
        props: ['submitFormData'],
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
            swatches: [],
            footerText: '',
        }),
        methods:{
            previewColor(){
                let selected_color = this.swatches;
                return this.swatches.push(selected_color);
            },
            submitForm(){
                const formData = {
                    links: this.links,
                    socialMidiasIcon: this.socialMidiasIcon,
                    footerText: this.footerText,
                    swatches: this.swatches,
                };

                
                this.$emit('submitFormData', formData)
            }
        }
    }
</script>