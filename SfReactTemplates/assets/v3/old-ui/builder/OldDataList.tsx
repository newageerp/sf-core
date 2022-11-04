import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import React, { Fragment, useEffect, useState } from 'react'
import { useHistory } from 'react-router-dom'


import { v4 as uuidv4 } from 'uuid'
import { BuilderWidgetProvider, useBuilderWidget } from './OldBuilderWidgetProvider'
import { useNaeSchema } from './OldSchemaProvider'
import { MainListColumn, useUIBuilder } from './OldUIBuilderProvider'
import { SFSSocketService } from '../../navigation/NavigationComponent';
import { SFSOpenViewModalWindowProps } from '@newageerp/v3.popups.mvc-popup';
import OldTable from '../OldTable'
import OldThead from '../OldThead'
import { getPropertyForPath } from '../../utils'
import { transformTdProps, transformThProps } from '../OldArrayFieldComponent'
import OldTbody from '../OldTbody'
import { defaultStrippedRowClassName } from '../OldTrow'


export interface SchemaListProps {
  listSize?: number
  tableSize?: string,
  columns: MainListColumn[]
  filter?: any
  filterStr?: string
  showPaging?: boolean
  onNavigate?: (
    schema: string,
    id: number | string,
    column: MainListColumn
  ) => void
  socketSubscribe?: string
}

export default function DataList(props: SchemaListProps) {
  const builderDefaults = useUIBuilder().defaults;

  const [socketId, setSocketId] = useState('')
  const parentElement = useBuilderWidget().element
  const [page, setPage] = useState(1)

  const history = useHistory()

  const { schema } = useNaeSchema()

  const listSize = props.listSize ? props.listSize : 20
  const columns = props.columns ? props.columns : []

  const fieldsToReturn = columns.map((e) =>
    e.path.split('.').slice(1).join('.')
  )

  const [getData, getDataParams] = OpenApi.useUList<any>(schema, [...fieldsToReturn, 'scopes'])

  const navigate = (
    schema: string,
    id: number | string,
    column: MainListColumn
  ) => {
    if (column.link === 10) {
      history.push('/u/' + schema + '/main/view/' + id)
    } else {
      SFSOpenViewModalWindowProps({
        id: id,
        schema: schema
      })
    }
  }

  const onNavigate = props.onNavigate ? props.onNavigate : navigate

  let filter: any = []
  if (props.filter) {
    filter = props.filter
  }
  if (props.filterStr) {
    try {
      filter = JSON.parse(
        props.filterStr.replaceAll(
          '@context.id',
          parentElement ? parentElement.id : -1
        )
      )
    } catch (e) { }
  }

  const loadData = () => {
    let sort = [
      {
        key: 'i.id',
        value: 'DESC'
      }
    ]
    const builderDefaultsSchema = builderDefaults.filter(f => f.config.schema === schema);
    if (builderDefaultsSchema.length > 0) {
      if (builderDefaultsSchema[0].config.defaultSort) {
        sort = JSON.parse(builderDefaultsSchema[0].config.defaultSort);
      }
    }

    getData(filter, page, listSize, sort)
  }

  useEffect(loadData, [parentElement, page])

  const pages = Math.ceil(getDataParams.data.records / listSize)

  useEffect(() => {
    if (parentElement.id > 0) {
      setSocketId(parentElement.id + '-' + uuidv4())
    }
  }, [parentElement])

  useEffect(() => {
    if (socketId && props.socketSubscribe) {
      SFSSocketService.subscribeToList(
        {
          key: socketId,
          data: JSON.parse(
            props.socketSubscribe.replaceAll(
              '@context.id',
              parentElement ? parentElement.id : -1
            )
          )
        },
        loadData
      )
    }

    return () => {
      if (socketId && props.socketSubscribe) {
        SFSSocketService.unSubscribeFromList({ key: socketId })
      }
    }
  }, [socketId])

  return (
    <Fragment>
      <OldTable
        thead={
          <OldThead
            columns={columns.map((c) => {
              const _path = c.titlePath ? c.titlePath : c.path
              const property = getPropertyForPath(_path)

              if (!property) {
                return {
                  props: {},
                  content: ''
                }
              }

              return transformThProps(
                {
                  props: {
                    size: props.tableSize,
                  },
                  content: !!c.customTitle ? c.customTitle : property.title
                },
                property,
                !!c.link && c.link > 0
              )
            })}
          />
        }
        tbody={
          <OldTbody
            data={getDataParams.data.data}
            callback={(item: any, index: number) => {
              const scopes = item.scopes ? item.scopes : [];
              let rowClassName = defaultStrippedRowClassName(index);
              scopes.forEach((scope: string) => {
                if (scope.indexOf('bg-row-color:') > -1) {
                  const scopeA = scope.split(":");
                  rowClassName = scopeA[1];
                }
              })

              return {
                columns: columns.map((c) => {
                  
                  const property = getPropertyForPath(c.path)

                  if (!property) {
                    return {
                      props: {
                        size: props.tableSize ? props.tableSize : undefined,
                      },
                      content: ''
                    }
                  }

                  const path = c.path.split('.')

                  let _item = item
                  if (path.length > 2) {
                    for (let i = 1; i < path.length - 1; i++) {
                      _item = _item[path[i]]
                    }
                  }

                  if (c.builderId) {
                    return {
                      content: <BuilderWidgetProvider builderId={c.builderId} element={item} options={{ builderId: c.builderId }} schema={schema} />,
                      props: {
                        className: 'text-left'
                      }
                    }
                  }

                  return transformTdProps({
                    property,
                    column: {
                      props: {
                        size: props.tableSize ? props.tableSize : undefined,
                      },
                      content: ''
                    },
                    item: _item,
                    tabField: {
                      key: path[path.length - 1],
                      link: c.link && c.link > 0 ? true : false,
                      linkNl: true,
                    },
                    navigate: onNavigate
                  })
                }),
                className: rowClassName
              }
            }}
          />
        }
      />
      {/* {props.showPaging && (
        <Paging pages={pages} activePage={page} setActivePage={setPage} />
      )} */}
    </Fragment>
  )
}
