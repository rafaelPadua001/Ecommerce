<template>
    <!-- <div>
        <Dashboard />
    </div> -->
    <v-container>
        <v-sheet class="py-2 px-2">
            <div v-if="!products">
                <v-row justify="center" no-gutters>
                    <v-col class="d-flex flex-column" cols="auto">
                        <!-- CartItems: {{ carts }} -->
                        <v-timeline direction="horizontal" line-inset="12">
                            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-home" fill-dot size="x-small">
                                <template v-slot:opposite>
                                    <v-row no-gutters>
                                        <v-col cols="auto">
                                            <v-card v-model="confirm" v-if="confirm" class="mx-auto" :width="600">
                                                <v-card-text>
                                                    <v-row fluid>
                                                        <v-col>
                                                            <v-row>
                                                                <v-col>
                                                                    <p class="text-start text-body-1">Contact
                                                                        Information
                                                                    </p>
                                                                    <v-divider></v-divider>
                                                                    <v-spacer></v-spacer>
                                                                </v-col>
                                                            </v-row>

                                                            <v-row>
                                                                <v-col>
                                                                    <v-form>
                                                                        <v-text-field v-if="!customer.email"
                                                                            v-model="email" label="email" required
                                                                            :rules="emailRules"
                                                                            prepend-icon="fas fa-envelope"
                                                                            placeholder="Email here...">

                                                                        </v-text-field>
                                                                        <v-text-field v-else v-model="customer.email"
                                                                            label="email" required :rules="emailRules"
                                                                            prepend-icon="fas fa-envelope"
                                                                            placeholder="Email here...">

                                                                        </v-text-field>
                                                                    </v-form>
                                                                </v-col>
                                                            </v-row>

                                                            <v-row>
                                                                <v-col>
                                                                    <v-checkbox
                                                                        label="Desejo receber ofertas e novidades"
                                                                        color="primary">

                                                                    </v-checkbox>
                                                                </v-col>
                                                            </v-row>

                                                            <v-row>
                                                                <v-col class="text-body-1 text-start">
                                                                    <p>Shipping address</p>
                                                                    <v-divider></v-divider>
                                                                </v-col>
                                                            </v-row>
                                                            <v-row>
                                                                <v-col v-if="Object.keys(this.address).length == 0">
                                                                    <v-form>
                                                                        <v-row>
                                                                            <v-col>
                                                                                <v-text-field
                                                                                    v-model="customer.first_name"
                                                                                    label="First Name">

                                                                                </v-text-field>

                                                                                {{ first_name }}
                                                                            </v-col>
                                                                            <v-col>
                                                                                <v-text-field
                                                                                    v-model="customer.last_name"
                                                                                    label="Last Name">

                                                                                </v-text-field>

                                                                                {{ last_name }}
                                                                            </v-col>
                                                                        </v-row>
                                                                        <v-row>
                                                                            <v-col>
                                                                                <v-text-field
                                                                                    v-model="shippment_address"
                                                                                    label="Address">

                                                                                </v-text-field>


                                                                            </v-col>

                                                                        </v-row>

                                                                        <v-row>
                                                                            <v-col>
                                                                                <v-text-field
                                                                                    v-model="shippment_complement"
                                                                                    label="Complemento (opcional)">

                                                                                </v-text-field>
                                                                            </v-col>

                                                                        </v-row>

                                                                        <v-row fluid>
                                                                            <v-col>
                                                                                <v-text-field v-model="shippment_city"
                                                                                    label="Cidade">

                                                                                </v-text-field>
                                                                            </v-col>
                                                                            <v-col cols="auto">

                                                                                <v-select v-model="select_uf"
                                                                                    :items="ufs"
                                                                                    :hint="`${select_uf.state}, ${select_uf.uf}`"
                                                                                    item-title="uf" item.value="state"
                                                                                    placeholder="UF" return-object
                                                                                    single-line
                                                                                    :placeholder="customerAddress.uf">

                                                                                </v-select>

                                                                            </v-col>
                                                                            <v-col>
                                                                                <v-text-field v-model="zip_code"
                                                                                    v-maska:[options] label="Zip Code">
                                                                                </v-text-field>
                                                                            </v-col>
                                                                            <v-col col="auto">
                                                                                <v-row no-gutters>
                                                                                    <v-col cols="auto">
                                                                                        <v-btn size="x-small"
                                                                                            variant="text"
                                                                                            color="warning"
                                                                                            @click="itemCart.cep = ''">Clear</v-btn>
                                                                                    </v-col>
                                                                                </v-row>
                                                                                <v-row no-gutters>
                                                                                    <v-col cols="auto">
                                                                                        <v-btn size="x-small"
                                                                                            variant="text" color="blue"
                                                                                            @click="cepDialogOpen">Buscar
                                                                                            Cep</v-btn>


                                                                                    </v-col>
                                                                                </v-row>

                                                                            </v-col>
                                                                        </v-row>
                                                                        <v-card-actions>
                                                                            <v-row>
                                                                                <v-col class="text-start">
                                                                                    <v-btn type="text"
                                                                                        :to="'/'">Return</v-btn>
                                                                                </v-col>
                                                                            </v-row>
                                                                            <v-row>
                                                                                <v-col class="text-end">
                                                                                    <v-btn @click="confirmNext()"
                                                                                        color="primary">Confirmar</v-btn>
                                                                                </v-col>
                                                                            </v-row>


                                                                        </v-card-actions>

                                                                    </v-form>

                                                                </v-col>
                                                                <v-col v-else>
                                                                    teste 1
                                                                </v-col>
                                                            </v-row>
                                                        </v-col>
                                                    </v-row>
                                                </v-card-text>
                                            </v-card>

                                            
                                        </v-col>

                                        <v-col v-if="confirm">
                                            <v-card class="d-flex justify-center flex-column" :width="500">

                                                <v-list :lines="false" density="compact" nav>

                                                    <v-list-item v-for="(item, index) in parsedProduct" :key="index"
                                                        :value="item">
                                                        <v-toolbar color="transparent">
                                                            <template v-slot:append>
                                                                <v-btn icon size="x-small"
                                                                    @click="removeItemDialog(item)">
                                                                    <v-icon icon="fas fa-trash"></v-icon>
                                                                </v-btn>
                                                            </template>
                                                        </v-toolbar>

                                                        <v-row>
                                                            <v-col class="d-flex child-flex">
                                                                <v-bagde color="grey" :content="item.shippment_quantity"
                                                                    :value="true" bordered>
                                                                    <v-img :width="4"
                                                                        :src="`/storage/products/${item.images[0]}`"
                                                                        :lazy-src="`/storage/products/${item.images[0]}`"
                                                                        aspect-ratio="16/9" cover>
                                                                        <template v-slot:placeholder>
                                                                            <div
                                                                                class="d-flex justify-center fill-height flex-column">
                                                                                <v-progress-circular
                                                                                    color="grey-lighten-4"
                                                                                    indeterminate></v-progress-circular>
                                                                            </div>
                                                                        </template>
                                                                    </v-img>
                                                                </v-bagde>
                                                            </v-col>
                                                            <v-col cols="auto" class="d-flex flex-child text-body-2">
                                                                <strong>{{ item.name }}</strong>
                                                            </v-col>

                                                            <v-col cols="auto" class="d-flex flex-child text-body-2">
                                                                <!-- <strong>Product Price:</strong> -->
                                                                R$ {{ item.cart_item_price }}
                                                            </v-col>
                                                        </v-row>


                                                        <v-row no-gutters class="text-subtitle-2 justify-end">
                                                            <strong>Quantity:</strong> {{
                item.shippment_quantity }}
                                                        </v-row>
                                                        <v-row no-gutters class="text-subtitle-2 justify-end">

                                                            <v-col cols="auto"
                                                                v-for="(color, index) in item.cart_item_colors"
                                                                :key="index">

                                                                <v-avatar :bg-color="color" :color="color" :width="1"
                                                                    rounded="10">
                                                                    <template v-slot:append>
                                                                        <!-- {{ parsedQuantityColors[index] }} -->
                                                                        <!-- <span>Available</span> -->
                                                                    </template>
                                                                </v-avatar>
                                                            </v-col>

                                                        </v-row>
                                                        <v-row no-gutters class="text-subtitle-2 justify-end">

                                                            <v-col cols="auto"
                                                                v-for="(size, index) in item.cart_item_size"
                                                                :key="index">

                                                                <v-avatar color="grey" :width="75" rounded="10">
                                                                    {{ size }}

                                                                </v-avatar>
                                                            </v-col>
                                                        </v-row>


                                                        <v-row fluid class="text-subtitle-2 justify-end">
                                                            <v-col cols="auto">
                                                                <p>shippment: {{ item.company }}</p>


                                                            </v-col>
                                                            <v-col cols="auto">
                                                                <p>R$ {{ item.shippment_price }}</p>


                                                            </v-col>

                                                        </v-row>

                                                        <v-row fluid class="text-subtitle-2 justify-end">
                                                            <v-col cols="auto">
                                                                <p>Total Delivery: R$ {{ item.delivery_price }}</p>
                                                            </v-col>
                                                            <v-col cols="auto">
                                                                <p>Total Price: R$ {{ item.total_price }}</p>
                                                            </v-col>
                                                        </v-row>
                                                        <v-divider></v-divider>
                                                        <v-spacer></v-spacer>
                                                    </v-list-item>

                                                </v-list>


                                            </v-card>

                                            <v-divider></v-divider>
                                            <v-spacer></v-spacer>
                                            <v-row>
                                                <v-col>
                                                    <v-input label="Discount coupon">
                                                        <template v-slot:append>
                                                            <v-btn color="grey" @click="couponApply">Apply</v-btn>
                                                        </template>
                                                        <v-text-field v-model="coupon" hide-details
                                                            label="Gift Card or offer code"></v-text-field>
                                                    </v-input>
                                                </v-col>
                                            </v-row>

                                            <v-spacer></v-spacer>
                                            <v-divider></v-divider>
                                            <v-row no-gutters>
                                                <v-col class="text-start text-subtitle-2">
                                                    <p>Subtotal: </p>
                                                </v-col>
                                                <v-col class="text-end text-subtitle-2">
                                                    {{ formattedSubtotal }}
                                                </v-col>
                                            </v-row>
                                            <v-row no-gutters>
                                                <v-col class="text-start text-subtitle-2">
                                                    <p>Shipping:</p>
                                                </v-col>
                                                <v-col class="text-end text-subtitle-2">
                                                    {{ formattedShippingPrice }}
                                                </v-col>
                                            </v-row>

                                            <v-divider></v-divider>

                                            <v-row fluid>
                                                <v-col class="text-start text-body-1">
                                                    <p><strong>Total:</strong></p>
                                                </v-col>
                                                <v-col class="text-end">
                                                    {{ formatedFinalValue }}
                                                </v-col>
                                            </v-row>


                                        </v-col>
                                    </v-row>

                                </template>
                            </v-timeline-item>

                            <v-timeline-item v-model="dataConfirm" v-if="dataConfirm && !confirm" dot-color="blue-darken-2"
                                icon="fas fa-check" fill-dot size="small" class="justify-start">
                                <template v-slot:opposite>
                                    
                                </template>

                                <v-card v-model="dataConfirm" v-if="dataConfirm && !confirm"
                                                class="d-flex mx-auto flex-column" :width="600">
                                                <v-card-text>
                                                    <v-row fluid>
                                                        <div class="container-relative">
                                                            <v-col v-for="(item, index) in parsedProduct" :key="index"
                                                                class="avatar-stack" cols="auto"
                                                                :style="{ 'z-index': parsedProduct.length - index, 'left': `${index * 10}px` }" 
                                                            >
                                                                <v-badge
                                                                    color="red"
                                                                    :content="item.shippment_quantity"
                                                                >
                                                                    <v-avatar :image="`/storage/products/${item.images[0]}`"
                                                                    :lazy-src="`/storage/products/${item.images[0]}`"
                                                                    aspect-ratio="16/9" :width="200" cover
                                                                    alt="Image Here" color="grey-lighten-2" no-gutters>


                                                                    <template>
                                                                        <div
                                                                            class="d-flex align-center justify-center fill-height">
                                                                            <v-progress-circular color="grey-lighten-4">
                                                                            </v-progress-circular>
                                                                        </div>
                                                                    </template>
                                                                    <!-- {{ item.images[0] }} -->
                                                                </v-avatar>

                                                                </v-badge>
                                                                

                                                            </v-col>
                                                        </div>
                                                   
                                                    
                                                        <div class="container-absolute">
                                                            <v-col cols="auto">
                                                            <v-row no-gutters>
                                                                <v-col class="text-body-1">
                                                                    <strong>Customer name:</strong>
                                                                    {{ customer.first_name }} {{ customer.last_name }}
                                                                </v-col>
                                                            </v-row>
                                                            <v-spacer></v-spacer>
                                                            <v-divider></v-divider>
                                                            <v-row no-gutters>
                                                                <v-col class="text-overline">
                                                                    <strong>CPF:</strong>
                                                                    {{ 'not found' }}
                                                                </v-col>
                                                            </v-row>
                                                            <v-row no-gutters>
                                                                <v-col class="text-body-2">
                                                                    <strong>Celular:</strong>
                                                                    {{ 'not found' }}
                                                                </v-col>
                                                            </v-row>

                                                            <v-spacer></v-spacer>
                                                            <v-divider></v-divider>

                                                            <v-row no-gutters>
                                                                <v-col class="text-body-2">
                                                                    <strong>Endereço:</strong>
                                                                    {{ address.endereco ? 'not register' :
                shippment_address }}
                                                                </v-col>
                                                            </v-row>
                                                            <v-row no-gutters>
                                                                <v-col class="text-body-2">
                                                                    <strong>Complemento:</strong>
                                                                    {{ address.complemento ? 'not register' :
                shippment_complement }}
                                                                </v-col>
                                                            </v-row>

                                                            <v-row no-gutters>
                                                                <v-col>
                                                                    <strong>Cidade:</strong> {{ address.cidade ? 'not register' : shippment_city}}
                                                                </v-col>
                                                            </v-row>


                                                            <div>
                                                                <p class="text-subtitle-2">
                                                                    <strong>Zip Code</strong> {{ address.cep ? 'not register' : zip_code }}
                                                                </p>
                                                                <p class="text-subtitle-2">
                                                                    <strong>UF:</strong> {{ address.UF ? 'not register'
                : select_uf.uf }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <!-- <p class="text-subtitle-2">
                                                                    <strong>Delivery: </strong>
                                                                    {{ selectedDelivery.currency }} {{
                selectedDelivery.price }}

                                                                </p> -->

                                                            </div>

                                                            <!-- <div>
                                                                <p>
                                                                    <strong>Total Value</strong>
                                                                    {{ selectedDelivery.currency }}
                                                                    {{ (parseFloat(selectedDelivery.price) +
                parseFloat(itemCart.price)).toFixed(2)
                                                                    }}
                                                                </p>

                                                            </div> -->

                                                        </v-col>
                                                        </div>
                                                        
                                                    </v-row>

                                                </v-card-text>

                                                <v-card-actions>
                                                    <v-btn-group>
                                                        <v-btn @click="returnConfirm()">voltar</v-btn>
                                                        <v-btn @click="confirmDatas()">Confirmar</v-btn>

                                                    </v-btn-group>
                                                </v-card-actions>
                                            </v-card>
                            </v-timeline-item>
                        </v-timeline>

                        <div>
                            <v-dialog v-model="removeDialog">
                                <v-card>
                                    <v-toolbar>
                                        <v-toolbar-title>{{ editedItem.name }}</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        Você deseja remover este Item ? {{ editedItem.cart_item_id }}
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-btn @click="closeRemoveItemDialog()">
                                            Close
                                        </v-btn>
                                        <v-btn color="error" @click="removeItemConfirm()">
                                            Confirm
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </div>
                    </v-col>
                </v-row>

            </div>
            <div v-else>
                CartItems: {{ carts }}
                <v-row align="center" justify="center">
                    <v-col cols="8" sm="8">
                        <v-timeline direction="horizontal" side="center" line-inset="12">
                            <v-timeline-item v-model="confirm" v-if="confirm" dot-color="blue-darken-2"
                                icon="fas fa-home" fill-dot size="x-small">
                                <template v-slot:opposite>
                                    <v-card class="d-flex justify-center flex-column" :max-width="500">
                                        <v-card-text>
                                            <v-row>
                                                <v-col class="d-flex child-flex">
                                                    <v-img :src="`/storage/products/${productImages}`"
                                                        :lazy-src="`/storage/products/${productImages}`"
                                                        aspect-ratio="16/9" :width="200" cover>

                                                    </v-img>


                                                </v-col>
                                                <v-col>
                                                    <div>
                                                        <p><strong>Name Product:</strong> {{ itemCart.name }}</p>
                                                    </div>


                                                    <div>
                                                        <p><strong>Color:</strong>
                                                            <v-card :color="itemCart.color">
                                                                color
                                                            </v-card>
                                                        </p>

                                                    </div>
                                                    <div>
                                                        <strong>Quantity:</strong> {{ itemCart.quantity }}
                                                    </div>
                                                    <div>
                                                        <v-row fluid>
                                                            <v-col>
                                                                <v-text-field v-model="zip_code" v-if="itemCart.cep"
                                                                    v-maska:[options] label="postal code"
                                                                    :placeholder="itemCart.cep" :value="itemCart.cep">
                                                                </v-text-field>
                                                                <v-text-field v-model="zip_code" v-else
                                                                    v-maska:[options] label="postal code"
                                                                    :placeholder="itemCart.cep">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col col="8" sm="4">

                                                                <v-btn size="x-small" variant="text" color="primary"
                                                                    @click="calculateDelivery">calculate</v-btn>
                                                                <v-btn size="x-small" variant="text" color="warning"
                                                                    @click="itemCart.cep = ''">Clear</v-btn>
                                                                <v-btn size="x-small" variant="text" color="blue"
                                                                    @click="cepDialogOpen">Buscar Cep</v-btn>


                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col v-if="respSearchAddress.length >= 1">
                                                                <strong>endereco:</strong> {{ address.complemento }},
                                                                <strong>Bairro:</strong> {{ respSearchAddress[0].bairro
                                                                }},
                                                                <strong>Logradouro:</strong> {{
                respSearchAddress[0].logradouro
            }},
                                                                <strong>CEP:</strong> {{ respSearchAddress[0].cep }} ,
                                                                <strong>Localidade:</strong> {{
                respSearchAddress[0].localidade
            }},
                                                                <strong>UF:</strong> {{ respSearchAddress[0].uf }}
                                                                <strong>DDD:</strong> {{ respSearchAddress[0].ddd }}
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>

                                                        </v-row>
                                                        <v-row>
                                                            <v-col col="12" sm="12">
                                                                <v-row>
                                                                    <v-col col="12" sm="12">
                                                                        <v-card class="mx-auto" :width="255">
                                                                            <v-list lines="three">
                                                                                <v-list-item
                                                                                    v-for="quotation in quotations"
                                                                                    :key="quotation.id"
                                                                                    :v-if="!quotation.error">

                                                                                    <template v-slot:prepend>

                                                                                        <v-avatar :width="120"
                                                                                            color="transparent-lighten-1">
                                                                                            <v-img
                                                                                                :src="quotation.company.picture"
                                                                                                :lazy-src="quotation.company.picture"
                                                                                                :placeholder="quotation.company.name">
                                                                                            </v-img>
                                                                                        </v-avatar>
                                                                                    </template>

                                                                                    <div>
                                                                                        <p class="text-body-2">
                                                                                            Price:
                                                                                            <strong>{{
                quotation.currency }}
                                                                                                {{ quotation.price
                                                                                                }}</strong>
                                                                                        </p>
                                                                                        <!--<p class="text-body-2">
                                                                                    Discount:
                                                                                    <strong>{{quotation.discount}}</strong>
                                                                                </p>
                                                                             
                                                                                 <p class="text-body-2" v-if="!quotation.error && itemCart.price">
                                                                                    total Value
                                                                                    <strong>
                                                                                        {{ 
                                                                                            (
                                                                                                (parseFloat(selectedDelivery.price) || 0) + 
                                                                                                (parseFloat(itemCart.price) || 0)).toFixed(2) 
                                                                                        }}
                                                                                    </strong>
                                                                                </p> -->
                                                                                        <p class="text-body-2">
                                                                                            Prazo de entrega:
                                                                                            <strong>
                                                                                                {{
                quotation.delivery_time
            }}
                                                                                                dias úteis
                                                                                            </strong>
                                                                                        </p>
                                                                                    </div>


                                                                                    <template v-slot:append>
                                                                                        <v-radio-group
                                                                                            v-model="selectedDelivery">
                                                                                            <v-radio
                                                                                                :value="quotation"></v-radio>
                                                                                        </v-radio-group>


                                                                                    </template>


                                                                                </v-list-item>
                                                                            </v-list>
                                                                        </v-card>

                                                                    </v-col>
                                                                </v-row>


                                                            </v-col>
                                                        </v-row>

                                                    </div>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-btn-group>
                                                <v-btn @click="confirmNext()">Confirmar</v-btn>
                                            </v-btn-group>
                                        </v-card-actions>
                                    </v-card>


                                    <!-- {{ itemCart }} -->
                                </template>
                                <div>
                                    <div>Confirmar pedido</div>
                                    <p>
                                        {{ itemCart.name }}
                                    </p>
                                </div>
                            </v-timeline-item>

                            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="x-small">
                                <template v-slot:opposite>
                                    <v-card v-model="dataConfirm" class="d-flex align-center flex-column"
                                        v-if="dataConfirm" :width="500">
                                        <v-card-text>
                                            <v-row>
                                                <v-col class="d-flex child-flex" cols="6">
                                                    <div>
                                                        <v-img :src="`/storage/products/${productImages}`"
                                                            :lazy-src="`/storage/products/${productImages}`"
                                                            aspect-ratio="16/9" :width="200" cover>


                                                            <template>
                                                                <div
                                                                    class="d-flex align-center justify-center fill-height">
                                                                    <v-progress-circular color="grey-lighten-4">
                                                                    </v-progress-circular>
                                                                </div>
                                                            </template>

                                                        </v-img>
                                                    </div>

                                                </v-col>
                                                <v-col col="12" md="4">

                                                    <div>

                                                        <p class="text-subititle-1">
                                                            <strong>Customer name:</strong>
                                                            {{ customer.first_name }}


                                                        </p>

                                                    </div>
                                                    <div>

                                                        <p class="text-subititle-1">
                                                            <strong>Customer lastname:</strong>
                                                            {{ customer.last_name }}

                                                        </p>

                                                    </div>
                                                    <div>
                                                        <p class="text-subtitle-2">
                                                            <strong>CPF:</strong>

                                                        </p>

                                                    </div>
                                                    <div>
                                                        <p class="text-subtitle-2">
                                                            <strong>Contact:</strong>
                                                            {{ address.telefone }}
                                                        </p>

                                                    </div>
                                                    <div>
                                                        <p class="text-subtitle-2"><strong>Celular:</strong> </p>

                                                    </div>
                                                    <div>
                                                        <p class="text-subtitle-2">
                                                            <strong>Endereço:</strong> {{ address.endereco }}
                                                        </p>
                                                        <p class="text-subtitle-2">

                                                            <strong>Complemento:</strong> {{ address.complemento }}
                                                        </p>
                                                        <p class="text-subtitle-2">
                                                            <strong>Bairro:</strong> {{ address.bairro }}
                                                        </p>
                                                        <p class="text-subtitle-2">
                                                            <strong>Cidade:</strong> {{ address.cidade }}
                                                        </p>
                                                    </div>

                                                    <div>
                                                        <p class="text-subtitle-2">
                                                            <strong>Zip Code</strong> {{ address.cep }}
                                                        </p>
                                                        <p class="text-subtitle-2">
                                                            <strong>UF:</strong> {{ address.UF }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-subtitle-2">
                                                            <strong>Delivery: </strong>
                                                            {{ selectedDelivery.currency }} {{ selectedDelivery.price }}

                                                        </p>

                                                    </div>

                                                    <div>
                                                        <p>
                                                            <strong>Total Value</strong>
                                                            {{ selectedDelivery.currency }}
                                                            {{ (parseFloat(selectedDelivery.price) +
                parseFloat(itemCart.price)).toFixed(2)
                                                            }}
                                                        </p>

                                                    </div>

                                                </v-col>
                                            </v-row>

                                        </v-card-text>

                                        <v-card-actions>
                                            <v-btn-group>
                                                <v-btn @click="returnConfirm()">voltar</v-btn>
                                                <v-btn @click="confirmDatas()">Confirmar</v-btn>

                                            </v-btn-group>
                                        </v-card-actions>
                                    </v-card>
                                </template>
                                <div>
                                    <div>Datas User and Delivery Confirm</div>
                                    <p>
                                        {{ itemCart.name }}
                                    </p>

                                </div>
                            </v-timeline-item>

                            <v-timeline-item dot-color="blue-darken-2" icon="fas fa-truck" fill-dot size="x-small">
                                <template v-slot:opposite>

                                </template>
                                <div>
                                    <div class="text-h6">
                                        <v-card v-model="finish" class="d-flex align-center flex-column" v-if="finish"
                                            :width="500">
                                            <v-card-text>
                                                <v-row>
                                                    <v-col class="d-flex child-flex" cols="6">
                                                        <div>

                                                            <v-img :src="`/storage/products/${productImages}`"
                                                                :lazy-src="`/storage/products/${productImages}`"
                                                                aspect-ratio="16/9" :width="200" cover>

                                                                <template>
                                                                    <div
                                                                        class="d-flex align-center justify-center fill-height">
                                                                        <v-progress-circular color="grey-lighten-4">
                                                                        </v-progress-circular>
                                                                    </div>
                                                                </template>

                                                            </v-img>

                                                        </div>
                                                    </v-col>
                                                    <v-col cols="6" md="4">
                                                        <div class="text-h6">
                                                            {{ itemCart.name }}
                                                        </div>
                                                        <div>
                                                            <p>
                                                                <strong>Total Value</strong>
                                                                {{ selectedDelivery.currency }}
                                                                {{ (parseFloat(selectedDelivery.price) +
                parseFloat(itemCart.price)).toFixed(2) }}
                                                            </p>

                                                        </div>

                                                        <div>
                                                            <strong>Cupom</strong> {{ itemCart.quantity }}
                                                        </div>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="8" sm="6">
                                                        <v-card class="mx-auto" :width="450">
                                                            <v-card-text>
                                                                <v-row>
                                                                    <v-col cols="8" sm="8">
                                                                        <v-radio-group v-model="paymentType" inline>
                                                                            <v-radio label="Debit" value="debit">

                                                                            </v-radio>
                                                                            <v-radio label="Credit" value="credit">

                                                                            </v-radio>
                                                                            <v-radio label="Pix" value="pix">

                                                                            </v-radio>
                                                                        </v-radio-group>
                                                                    </v-col>
                                                                </v-row>

                                                                <v-row>
                                                                    <v-col>
                                                                        <div v-if="paymentType == 'debit'">
                                                                            <v-card>
                                                                                <DebitForm :paymentType="paymentType"
                                                                                    :id="this.itemCart.id"
                                                                                    :name="this.itemCart.name"
                                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                                    :quantity="this.itemCart.quantity"
                                                                                    :delivery="selectedDelivery"
                                                                                    :description="this.itemCart.description"
                                                                                    :image="this.itemCart.images"
                                                                                    :color="this.itemCart.color"
                                                                                    :address="this.address" />
                                                                            </v-card>
                                                                        </div>
                                                                        <div v-if="paymentType == 'credit'">
                                                                            <v-card>
                                                                                <CreditForm :paymentType="paymentType"
                                                                                    :name="this.itemCart.name"
                                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                                    :quantity="this.itemCart.quantity"
                                                                                    :delivery="selectedDelivery"
                                                                                    :description="this.itemCart.description"
                                                                                    :image="this.itemCart.images"
                                                                                    :color="this.itemCart.color" />


                                                                            </v-card>
                                                                        </div>
                                                                        <div v-if="paymentType == 'pix'">
                                                                            <v-card>
                                                                                <PixForm :paymentType="paymentType"
                                                                                    :name="this.itemCart.name"
                                                                                    :totalValue="(parseFloat(selectedDelivery.price) + parseFloat(itemCart.price)).toFixed(2)"
                                                                                    :quantity="this.itemCart.quantity"
                                                                                    :delivery="selectedDelivery"
                                                                                    :description="this.itemCart.description"
                                                                                    :image="this.itemCart.images">
                                                                                </PixForm>
                                                                            </v-card>
                                                                        </div>
                                                                    </v-col>
                                                                </v-row>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-btn-group>
                                                    <v-btn @click="returnConfirmDatas()">Voltar</v-btn>


                                                </v-btn-group>
                                            </v-card-actions>
                                        </v-card>
                                    </div>
                                    <p>

                                    </p>
                                </div>
                                <div>
                                    <div>Finalizar</div>
                                    <p>
                                        {{ itemCart.name }}
                                    </p>

                                </div>
                            </v-timeline-item>

                        </v-timeline>
                    </v-col>
                </v-row>
            </div>
        </v-sheet>
    </v-container>


    <div>
        <v-dialog v-model="cepDialog" persistent width="1024">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Busca Cep</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>

                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="uf" label="UF"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="city" label="Cidade"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="bairro" label="Bairro"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="searchAddress" label="logradouro"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="complemento" label="Complemento"></v-text-field>
                            </v-col>

                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>


                    <div v-if="respSearchAddress.length >= 1">
                        <v-row>
                            <v-col>
                                CEP: {{ respSearchAddress[0].cep }} ,
                                Logradouro: {{ respSearchAddress[0].logradouro }},
                                Localidade: {{ respSearchAddress[0].localidade }},
                                UF: {{ respSearchAddress[0].uf }}
                                DDD: {{ respSearchAddress[0].ddd }}
                            </v-col>
                        </v-row>

                    </div>

                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="cepDialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="searchToAddress">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { vMaska } from "maska";


const options = { mask: '#####-####' };
const myValue = ref('');

const phoneOptions = { mask: '55+ (##) #####-####' };
const phoneMask = ref('');
</script>

<script>
import Dashboard from '../Auth/Dashboard.vue'
import DebitForm from '../Payment/DebitForm.vue'
import CreditForm from '../Payment/CreditForm.vue'
import PixForm from '../Payment/PixForm.vue'
import cartStorage from '@/Services/CartStorage/CartStorage';
import axios from "axios";

export default {
    name: 'Checkout',
    components: {
        Dashboard,
        DebitForm,
        CreditForm,
        PixForm,
    },

    data: () => ({
        itemCart: [],
        quotations: [],
        customer: [],
        address: [],
        respSearchAddress: [],
        zip_code: false,
        selectedDelivery: false,
        confirm: true,
        dataConfirm: false,
        finish: false,
        paymentType: false,
        productImages: false,
        colors: false,
        cepDialog: false,
        searchAddress: null,
        postal_code: null,
        uf: null,
        city: null,
        bairro: null,
        complemento: null,
        carts: [],
        shippment_address: '',
        shippment_complement: '',
        shippment_city: '',
        select_uf: { state: 'Distrito Federal', uf: 'DF' },
        email: '',
        emailRules: [
            v => !!v || 'O email é obrigatório',
            v => /.+@.+\..+/.test(v) || 'Email inválido',
        ],
        ufs: [
            { state: 'Distrito Federal', uf: 'DF' },
            { state: 'Goiás', uf: 'GO' },
            { state: 'Maranhão', uf: 'MA' },
        ],
        index: -1,
        editedItem: {},
        removeDialog: false,
    }),
    computed: {
        parsedProduct() {
            return this.carts.map((item) => {
                return {
                    ...item,
                    cart_item_colors: JSON.parse(item.cart_item_colors),
                    cart_item_size: JSON.parse(item.cart_item_size),
                    images: JSON.parse(item.images),
                }
            });
        },
        subtotal() {
            return this.carts.reduce((acc, item) => {
                return acc + (item.cart_item_price * item.shippment_quantity);
            }, 0)
        },
        formattedSubtotal() {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(this.subtotal);
        },
        shipping_price() {
            return this.carts.reduce((acc, item) => {
                const total_shipping = parseFloat(item.delivery_price);
                return acc + (isNaN(total_shipping) ? 0 : total_shipping);
            }, 0);
        },
        formattedShippingPrice() {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(this.shipping_price);
        },
        finalValue() {
            return this.carts.reduce((acc, item) => {
                const total_value = parseFloat(item.total_price);
                return acc + (isNaN(total_value) ? 0 : total_value);
            }, 0);
        },
        formatedFinalValue() {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(this.finalValue);
        }
    },
    methods: {
        getProducts() {
            let url = window.location.href;

            // Use uma expressão regular para encontrar o número no final da URL
            let itemId = url.match(/\d+$/);

            // Verifique se o número foi encontrado
            if (itemId !== null) {
                // Converta o número para um valor inteiro
                itemId = parseInt(itemId[0]);

                axios.get(`/cartItem/checkout/${itemId}`)
                    .then((response) => {
                        this.productImages = JSON.parse(response.data.images);
                        this.colors = JSON.parse(response.data.colors);

                        return this.itemCart = response.data;
                    })
                    .catch((response) => {
                        return alert('Error: ' + response);
                    })

            }
        },
        getCustomer() {
            axios.get('/customer')
                .then((response) => {
                    return this.customer = response.data;

                })
                .catch((response) => {
                    return alert('Error:' + response);
                });
        },
        getAddress() {
            axios.get('/address')
                .then((response) => {
                    return this.address = response.data;
                })
                .catch((response) => {
                    return alert('Error: ' + response)
                })
        },
        searchToAddress() {
            const data = {
                address: this.searchAddress,
                postal_code: this.postal_code,
                uf: this.uf,
                city: this.city,
                bairro: this.bairro,
                complemento: this.complemento
            };

            axios.get(`https://viacep.com.br/ws/${this.uf}/${this.city}/${this.searchAddress.replace(/\s/g, '+')}/json/`)
                .then((response) => {
                    this.itemCart.cep = " ";
                    this.itemCart.cep = response.data[0].cep;
                    this.address.endereco = response.data[0].logradouro;
                    this.address.bairro = response.data[0].bairro;
                    this.address.cep = response.data[0].cep;
                    this.address.cidade = response.data[0].cidade;
                    this.address.UF = response.data[0].uf;
                    //this.address.complemento = response.data[0].complemento;
                    //this.cepDialog = false;
                    this.saveAddress(
                        this.itemCart.cep,
                        this.address.endereco,
                        this.address.bairro,
                        this.city,
                        response.data[0].uf,
                        response.data[0].ibge,
                        this.complemento,
                    );

                    return this.respSearchAddress.push(response.data[0]);

                })
                .catch((response) => {
                    return alert('Error :', response);
                })
        },
        saveAddress(cep, endereco, bairro, city, uf, ibge, complemento) {

            const newData = {
                address: endereco,
                postal_code: cep,
                uf: uf,
                city: city,
                bairro: bairro,
                code_ibge: ibge,
                complemento: complemento,

            };
            console.log(newData);
            axios.post('/saveSearchAddress', newData)
                .then((response) => {

                    return this.address = response.data;

                })
                .catch((response) => {
                    return alert('ERROR: ', response);
                });
        },
        calculateDelivery() {
            const data = {
                postal_code: this.zip_code,
                height: this.itemCart.height,
                width: this.itemCart.width,
                length: this.itemCart.length,
                weight: this.itemCart.weight,
                price: this.itemCart.price,
                quantity: this.itemCart.quantity,
            }
            axios.post('/api/calculateDelivery', data)
                .then((response) => {
                    return this.quotations = response.data;
                })
                .catch((response) => {
                    alert('Error : ' + response);
                })
        },
        cepDialogOpen() {
            this.cepDialog = true;

        },
        confirmNext() {
            this.dataConfirm = true;
            return this.confirm = false;
        },
        returnConfirm() {
            this.dataConfirm = false;
            return this.confirm = true;
        },
        confirmDatas() {
            this.dataConfirm = false;
            return this.finish = true;
        },
        returnConfirmDatas() {
            this.dataConfirm = true;
            this.finish = false;
        },
        couponApply() {
            alert('Working this...');
        },
        removeItemDialog(item) {
            this.index = this.carts.findIndex(cartItem => cartItem.cart_item_id === item.cart_item_id);
            this.editedItem = Object.assign({}, item);
            this.removeDialog = true;
        },
        removeItemConfirm() {

            this.carts.splice(this.index, 1);
            this.closeRemoveItemDialog();

        },
        closeRemoveItemDialog() {
            this.removeDialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, '');
                this.index = -1;
            });
        }
    },
    created() {
        this.getProducts();
        this.getCustomer();
        this.getAddress();
        this.carts = cartStorage.getCart();
        if (!this.carts) {
            this.$router.push({ name: 'cart' });
        }
        console.log(this.carts);
    }
}
</script>

<style scoped>
.avatar-stack {
    position: absolute;
    transition: transform 1s ease;
}

.avatar-stack:hover {
    transform: translateY(-10px);
}

.container-relative {
    position: relative;
    height: 100%; 
}
.container-absolute {
    position: relative;
    margin-left: 50%;
}
</style>