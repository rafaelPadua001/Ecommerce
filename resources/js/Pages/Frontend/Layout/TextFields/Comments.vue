<template>
    <v-row>
        <v-col class="d-flex justify-center flex-column" cols="auto" md="12" sm="12">
            <v-card elevation="0" >
                <v-card-title>
                    <v-avatar color="surface-variant"  v-if="this.customer">
                        <template v-slot="prepend" >
                            <v-img :src="`./storage/avatars/${this.customer.first_name}`"
                                :lazy-src="`./storage/avatars/${this.customer.last_name}`" cover>

                            </v-img>
                        </template>
                    </v-avatar>
                    <strong v-if="customer">{{ customer.first_name }} {{ customer.last_name }}</strong>
                   
                </v-card-title>
                <v-card-text>
                    <v-textarea v-model="comment" counter label="Live you'r comment" :rules="rules" :model-value="comment"
                        variant="filled" clear-icon="fas fa-close" clearable>
                        <template v-slot:append>

                            <v-btn v-if="comment.length >= 1" icon variant="plain" size="x-small" @click="sendComment()">
                                <v-icon icon="fas fa-paper-plane"></v-icon>

                            </v-btn>
                        </template>

                    </v-textarea>

                </v-card-text>

            </v-card>
           

        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';

export default {
    props: ['customer', 'product'],
    data: () => ({
        comments: [],
        value: 'comment here!',
        rules: [v => v.length <= 250 || 'Max 250 characteres'],
        comment: '',
    }),
    methods: {
        sendComment() {
            const data = {
                product_id: this.product.id,
                user_id: this.customer.id,
                message: this.comment 
            };
            axios.post(`/api/comment/create`, data)
            .then((response) => {
                this.$emit('create-comment', response.data);
                this.comments.push(response.data);
            })
            .catch((response) => {
                alert('Error: ' + response);
            })
          
        }
    }
}
</script>