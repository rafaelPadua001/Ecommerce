<template>
    <v-row>
       <v-col class="d-flex justify-center flex-column" cols="8" md="6" sm="3">
            <v-card>
                <v-card-title>
                    <v-avatar
                        color="surface-variant"
                        >
                        <template v-slot="prepend">
                            <v-img
                                :src="`./storage/avatars/${customer.name}`"
                                :lazy-src="`./storage/avatars/${customer.name}`"
                                cover
                            >

                            </v-img>
                        </template>
                    </v-avatar>
             
                        
                    <strong>{{ customer.first_name }} {{ customer.last_name }}</strong>
                   
                </v-card-title>
                <v-card-text>
                    <v-textarea
                        v-model="comment"
                        counter
                        label="Live you'r comment"
                        :rules="rules"
                        :model-value="comment" 
                        variant="filled"
                        clear-icon="fas fa-close"
                        clearable
                    >
                        <template v-slot="append">
                            <v-btn
                                variant="text"
                                @click="comment.reset()"
                            >
                                Clear
                            </v-btn>
                        </template>
                        
                    </v-textarea>

                    <v-btn
                        v-if="comment.length >= 1"
                        icon
                        variant="plain"
                        @click="sendComment()"
                    >
                        <v-icon icon="fas fa-paper-plane"></v-icon>
                       
                    </v-btn>
                </v-card-text>
            </v-card>

           
        </v-col>
    </v-row>
    
</template>

<script>
    export default {
        props: ['customer'],
        data: () => ({
            value: 'comment here!',
            rules: [v => v.length <= 250 || 'Max 250 characteres'],
            comment: '',
        }),
        methods: {
            sendComment(){
                alert('Send comment...');
            }
        }
    }
</script>