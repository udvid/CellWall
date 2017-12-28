<!--
How to create a modal dialog?
Supply an id attribute to the modal class.
Use the id of modal class in the modalTitle class, modalContent class, modalFooter class and modalWall class.
Example: If the title of the modal class is "myModal", the modalTitle id will be "myModal-Title".
-->

<div id="commonModal" class="modal clickHider hidden">
  <div class="mf-fixedContainer">
    <div class="modalContainer">
      <div class="modalHeader">
        <table class="mf-fullWidth">
          <tr>
            <td id="commonModal-Title" class="modalTitle"></td>
            <td class="mf-right"><a href="#" class="mf-imageBtn imgCenter actionBtn modalCloseBtn" data-subject="General" data-action="closeModal" title="Close">&nbsp;</a></td>
          </tr>
        </table>
      </div>
      <div id="commonModal-Content" class="modalContent"></div>
      <div id="commonModal-Footer" class="modalFooter"></div>
    </div>
  </div>
</div>
<div id="commonModal-Wall" class="modalWall hidden"></div>
