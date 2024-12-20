begin
with recursive ,recursive_cte (id, name, order_no, path, icon, parent_id,
                              status, created_by, updated_by, deleted_by, deleted_at, created_at, updated_at, type, parent_level)
                   as (select id,
                              name,
                              order_no,
                              path,
                        ,
                              icon,
                              parent_id,
                              status,
                              created_by,
                              updated_by,
                              deleted_by,
                              deleted_at,
                              created_at,
                              updated_at,
                              type,
                              1 as parent_level
                       from permissions
                       where permissions.id in (select permission_actions.permission_id
                                                from permission_actions
                                                where permission_actions.deleted_at is null
                                                  and permission_actions.id in
                                                      (select role_permission_actions.permission_action_id
                                                       from role_permission_actions
                                                       where role_permission_actions.deleted_at is null
                                                         and role_permission_actions.role_id in
                                                             (select user_roles.role_id
                                                              from user_roles
                                                              where user_roles.deleted_at is null
                                                                and user_roles.user_id = userId)))
                         and permissions.deleted_at is null

                       union all

                       select p.id,
                              p.name,
                              p.order_no,
                              p.path,

                              p.icon,
                              p.parent_id,
                              p.status,
                              p.created_by,
                              p.updated_by,
                              p.deleted_by,
                              p.deleted_at,
                              p.created_at,
                              p.updated_at,
                              p.type,
                              rc.parent_level + 1
                       from permissions p
                                join recursive_cte rc ON p.id = rc.parent_id)
select distinct id
from recursive_cte
order by id;
END
