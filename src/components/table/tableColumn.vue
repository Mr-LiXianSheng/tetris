<template>
  <div class="table-column">
    <solt></solt>
  </div>
</template>

<script>
export default {
  name: 'TableColumn',
  props: ['prop', 'label']
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

.table-column {
  flex: 1;
  text-align: center;
  height: 40px;
  line-height: 40px;
  font-weight: bold;
  color: @main-color;
  box-shadow: 0 0 3px @main-color;
}
</style>
