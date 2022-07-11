<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import axios from 'axios'

const dialogs = ref([]);

const messages = ref(['test 1', 'test 2']);
const message = ref('');


if (route()?.params?.user_id) {
  if (true) { //if there is no such dialog
    axios.post('/dialogs/create', {'user_id': route().params.user_id})
        .then(response => {
          location.href = route('chat', response.data.dialog_id); //todo fix it
        })
  }
} else if (route()?.params?.id) {
  //todo sockets
  axios.get('/messages/' + route().params.id)
      .then(response => {
        messages.value = response.data.messages;
      })
  setInterval(() => {
    axios.get('/messages/' + route().params.id)
        .then(response => {
          messages.value = response.data.messages;
        })
  }, 1000)
} else {
  axios.get('/dialogs')
      .then(response => {
        dialogs.value = response.data.dialogs ?? [];
      })
}

const dialogId = ref(route().params.id ?? null);


const sendMessage = () => {
  axios.post('/messages/create', {'dialog_id': dialogId.value, 'message': message.value})
      .then(response => {
        console.log(response)
      })
  messages.value.push({'text': message.value});
  message.value = '';
}
</script>

<template>
    <Head title="Chat" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                      <div v-if="dialogId">
                        Messages:
                        <ul>
                          <li v-for="message in messages">
                            {{message.text}}
                          </li>
                        </ul>
                        <input type="text" v-model="message">
                        <br>
                        <button class="border p-2" @click="sendMessage">send</button>
                      </div>
                      <div v-else>
                        Dialogs:
                        <ul>
                          <li v-for="dialog in dialogs">
                            <Link :href="route('chat', dialog.id)"><span v-for="user in dialog.users">{{user.name}} </span></Link>
                          </li>
                        </ul>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
