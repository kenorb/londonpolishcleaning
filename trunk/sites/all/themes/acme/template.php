<?php
// $Id$

/*
 * Provide the login/register link at the top
 */
function acme_preprocess_page(&$variables){
  // Who is looking at the page?
  global $user;
  
  // If the user is not logged in...
  if ($user->uid == 0) {
    $login = l(t('Log In'), 'user/login');
    $signup = l(t('Sign Up'), 'user/register');
    $variables['login_box'] = $login . ' | ' . $signup;
  }
  // If the user is logged in...
  else {
    $logout = l(t('Log out, !name', array('!name' => $user->name)), 'logout');
    $variables['login_box'] = $logout;
  }
  
  if ($variables['is_front']){
    $file = path_to_theme() . '/images/home-bike.jpg'; 
    $alt = t('A Beautiful white bike leaning against a blue wall in the mission district.');
    $title = t('Beautiful white bike');
    $home_image = theme('image', $file, $alt, $title);
    $variables['home_image'] = $home_image;
  }
}
/*
 * Change node submitted by formatting
 */
function acme_node_submitted($node) {
  $old =  t('Submitted by !username on @datetime', 
    array(
    '!username' => theme('username', $node), 
    '@datetime' => format_date($node->created),
  ));
  
  $date = format_date($node->created);
  $date = '<span class="date">' . $date . '</span>';
  
  $author = t('by !username', array('!username' => theme('username', $node)));
  $author = '<span class="author">' . $author . '</span>';
  
  $comment_count = format_plural($node->comment_count, '1 comment', '@count comments');
  $comment_count = '<span class="comment-count">' . $comment_count . '</span>';
  
  $submitted = $date . ' | ' . $author;
  $submitted .= ($comment_count)? ' | ' . $comment_count: '';
  
  return $submitted;
}

/*
 * Modify the breadcrumb to include a single arrow instead of a double arrow
 */
function acme_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    return '<div class="breadcrumb">' . implode(' > ', $breadcrumb) . '</div>';
  }
}

/*
 * Modify the pager to include some | pipes |
 */
function acme_pager($tags = array(), $limit = 10, $element = 0, $parameters = array(), $quantity = 9) {
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', (isset($tags[0]) ? $tags[0] : t('« first')), $limit, $element, $parameters);
  $li_previous = theme('pager_previous', (isset($tags[1]) ? $tags[1] : t('‹ previous')), $limit, $element, 1, $parameters);
  $li_next = theme('pager_next', (isset($tags[3]) ? $tags[3] : t('next ›')), $limit, $element, 1, $parameters);
  $li_last = theme('pager_last', (isset($tags[4]) ? $tags[4] : t('last »')), $limit, $element, $parameters);

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => 'pager-first', 
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => 'pager-previous', 
        'data' => $li_previous . '|',
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => 'pager-ellipsis', 
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => 'pager-item', 
            'data' => theme('pager_previous', $i, $limit, $element, ($pager_current - $i), $parameters),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => 'pager-current', 
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => 'pager-item', 
            'data' => theme('pager_next', $i, $limit, $element, ($i - $pager_current), $parameters),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => 'pager-ellipsis', 
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => 'pager-next', 
        'data' => '|' . $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => 'pager-last', 
        'data' => $li_last,
      );
    }
    return theme('item_list', $items, NULL, 'ul', array('class' => 'pager'));
  }
}

