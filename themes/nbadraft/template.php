<?php
/**
 * @file
 * Theme functions
 */

// Include all files from the includes directory.
$includes_path = dirname(__FILE__) . '/includes/*.inc';
foreach (glob($includes_path) as $filename) {
  require_once dirname(__FILE__) . '/includes/' . basename($filename);
}

/**
 * Implements template_preprocess_page().
 */
function nbadraft_preprocess_page(&$variables) {
  // Add copyright to theme.
  if ($copyright = theme_get_setting('copyright')) {
    $variables['copyright'] = check_markup($copyright['value'], $copyright['format']);
  }

  if (drupal_is_front_page()) {
      $variables['main_form'] = drupal_render(drupal_get_form('nbadraft_main_form'));
  }
}

/**
 * Implements hook_form()
 */
function nbadraft_main_form($form, &$form_status) {
    $form['draft_team'] = array(
        '#type' => 'select',
        '#title' => t('Draft Team'),
        '#options' => get_vocabulary_dropdown_values(3), // TODO: dynamic vid
    );

    $form['draft_year'] = array(
        '#type' => 'select',
        '#title' => t('Draft Year'),
        '#options' => get_vocabulary_dropdown_values(2), // TODO: dynamic vid
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit')
    );

    if (!empty($form_status['storage']['results'])) {
        $results = $form_status['storage']['results'];

        foreach ($results as $result) {
            $form['results'][] = array(
                '#markup' => render($result)
            );
        }
    }

    return $form;
}

/*
 * Implements hook_form_submit()
 */
function nbadraft_main_form_submit($form, &$form_status) {
    $form_status['rebuild'] = TRUE;

    $draft_team_tid = $form_status['values']['draft_team'];
    $draft_year_tid = $form_status['values']['draft_year'];

    $nodes = get_draft_picks_for_team_year($draft_year_tid, $draft_team_tid);

    $form_status['storage']['results'] = array();

    foreach ($nodes as $node) {
        $form_status['storage']['results'][] = node_view($node, 'teaser');
    }
}

function get_vocabulary_dropdown_values($vid) {
    $q = db_select('taxonomy_term_data', 't')
        ->fields('t')
        ->condition('vid', $vid)
        ->execute();

    $data = $q->fetchAllAssoc('tid');
    $select = array();

    foreach ($data as $term) {
        $select[$term->tid] = $term->name;
    }

    return $select;
}

function get_draft_picks_for_team_year($draft_year, $draft_team) {
    $q = db_select('node', 'n')
        ->fields('n', array('nid'));

    $q->join('field_data_field_draft_team', 'dt', 'n.nid = dt.entity_id');
    $q->join('field_data_field_draft_year', 'dy', 'n.nid = dy.entity_id');

    $result = $q->condition('dt.field_draft_team_tid', $draft_team)
        ->condition('dy.field_draft_year_tid', $draft_year)
        ->execute();

    $nids = $result->fetchAllAssoc('nid');

    $nodes = array();

    foreach ($nids as $row) {
        $node = node_load($row->nid);

        if ($node) {
            $nodes[] = $node;
        }
    }

    return $nodes;
}
