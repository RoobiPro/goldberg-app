<?php

function getRoleName($rolenr) {
  if ($rolenr == 0) {
    return 'Viewer';
  } else if ($rolenr == 1) {
    return 'Editor';
  } else if ($rolenr == 2) {
    return 'Admin';
  } else {
    return 'Role: ' + $rolenr;
  }
};
