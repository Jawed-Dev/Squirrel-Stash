  <template>
    <router-view/>
    <ContainerHeader/>
  </template>

<script setup>
  import ContainerHeader from '@/component/container/ContainerHeader.vue';
</script>


<!-- <transition
        enter-active-class="transition-opacity duration-500"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-500"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <router-view></router-view>
      </transition> -->


      <!-- <transition
  mode="out-in"
  @after-leave="handleAfterLeave"
  @after-enter="handleAfterEnter"
  ...>
  <router-view></router-view>
</transition> -->